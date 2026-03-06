<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingPrints;
use App\Models\Cattle;
use App\Models\CattleBooking;
use App\Models\Customer;
use App\Models\DeliveryLocation;
use App\Models\GenTotalExpense;
use App\Models\PaymentReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use HTMLPurifier;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    function index()
    {
        $pageTitle = 'Booking List';
        $latestBookingIds = Booking::selectRaw('MAX(id) as id')
            ->groupBy('booking_number');
        $bookings = Booking::with(['customer', 'delivery_location'])
            ->whereIn('id', $latestBookingIds->pluck('id'))
            ->searchable(['customer:first_name', 'booking_number'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());


        $cattles = Cattle::where('status', 1)->get();
        // dd($bookings);

        return view('admin.booking.index', compact('pageTitle', 'bookings', 'cattles'));
    }

    function create()
    {
        $pageTitle = 'Booking Create';
        $cattles = Cattle::with('lastCattleRecord', 'cattle_expenses')->where('status', 1)->get();

        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.booking.create', compact('pageTitle', 'cattles', 'customers'));
    }

    function store(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'customer_id'     => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'new_customer' && !Customer::where('id', $value)->exists()) {
                        $fail('The selected customer is invalid.');
                    }
                },
            ],
            'cus_name'        => ['required_if:customer_id,new_customer'],
            'cus_comp_name'   => ['required_if:customer_id,new_customer'],
            'contact_number'  => ['required_if:customer_id,new_customer'],
            'cus_address'     => 'nullable|string',
            'ref_name'        => 'nullable|string',
            'ref_cont_number' => 'nullable|string',
            'booking_type'    => ['required', 'numeric', Rule::in([1, 2])],
            'payment_method'  => ['required', 'string'],
            'delivery_date'   => ['required', 'date_format:m/d/Y'],
            'distric_city'    => ['required', 'string'],
            'area_location'   => ['required', 'string'],
            'booking_date'    => ['required', 'date_format:m/d/Y'],
        ], [
            'delivery_date.date'           => 'The delivery date must be a valid date.',
            'delivery_date.after_or_equal' => 'Delivery date cannot be in the past date.',
        ]);

        // Step 2: Validate cattle array with custom rules
        $validator = Validator::make($request->all(), [
            'cattles'                 => ['required', 'array', 'min:1'],
            'cattles.*.cattle_id'     => ['required', 'exists:cattles,id'],
            // 'cattles.*.delivery_date' => ['required', 'date_format:d/m/Y', 'after_or_equal:today'],
        ]);

        // Step 3: Custom after-validation for delivery_date vs. purchase_date
        $validator->after(function ($validator) use ($request) {
            foreach ($request->cattles as $index => $item) {
                if (!empty($item['cattle_id']) && !empty($item['delivery_date'])) {
                    $cattle = Cattle::find($item['cattle_id']);

                    if ($cattle && $cattle->purchase_date) {
                        $purchaseDate = Carbon::parse($cattle->purchase_date);

                        try {
                            $deliveryDate = Carbon::createFromFormat('d/m/Y', $item['delivery_date']);
                        } catch (\Exception $e) {
                            $validator->errors()->add("cattles.$index.delivery_date", 'Invalid delivery date format.');
                            continue;
                        }

                        if ($deliveryDate->lt($purchaseDate)) {
                            $validator->errors()->add(
                                "cattles.$index.delivery_date",
                                'Delivery date must be after or equal to the purchase date (' . $purchaseDate->format('d/m/Y') . ').'
                            );
                        }
                    }
                }
            }
        });



        DB::beginTransaction();

        try {

            // Convert the custom formatted date to timestamp
            $deliveryDate = Carbon::createFromFormat('d/m/Y', $request->input('delivery_date'));
            $bookingDate = Carbon::createFromFormat('d/m/Y', $request->input('booking_date'));

            // -------------------------------------Customer Create-------------------------------------
            $purifier  = new HTMLPurifier();
            if ($request->customer_id === 'new_customer') {
                $customer = new Customer();
                $customer->first_name      = $request->cus_name;
                $customer->company_name    = $request->cus_comp_name;
                $customer->phone           = $request->contact_number;
                $customer->address         = $purifier->purify($request->cus_address);
                $customer->ref_name        = $request->ref_name;
                $customer->ref_cont_number = $request->ref_cont_number;
                $customer->save();
            }
            // -------------------------------------End customer Create-------------------------------------


            // -------------------------------------Make Booking number -------------------------------------
            $prefix      = $request->booking_type == 1 ? 'INS-' : 'EID-';
            $lastBooking = Booking::where('booking_type', $request->booking_type)->orderBy('id', 'desc')->first();

            if ($lastBooking) {
                $numberPart      = Str::of($lastBooking->booking_number)->after($prefix);
                $incrementNumber = (int) $numberPart->value + 1;
            } else {
                $incrementNumber = 1;
            }
            $bookingNumber       = $prefix . str_pad($incrementNumber, 6, '0', STR_PAD_LEFT);
            // -------------------------------------End make Booking number -------------------------------------


            $totalSalePrice    = collect($request->cattles)->sum('sale_price');
            $totalAdvancePrice = collect($request->cattles)->sum('advance_price');

            // -------------------------------------Booking create-------------------------------------
            $booking = new Booking();
            $booking->customer_id          = isset($customer) ? $customer->id : $request->customer_id;
            $booking->booking_type         = $request->booking_type;
            $booking->booking_number       = $bookingNumber;
            $booking->payment_method       = $request->payment_method;
            $booking->sale_price           = $totalSalePrice;
            $booking->advance_price        = $totalAdvancePrice;
            $booking->due_price            = $totalSalePrice - $totalAdvancePrice;
            $booking->delivery_date        = $deliveryDate->toDateTimeString();
            $booking->booking_date        = $bookingDate->toDateTimeString();
            $booking->total_payment_amount = $totalAdvancePrice;
            $booking->status = 1;
            $booking->save();
            // -------------------------------------End booking create-------------------------------------



            // -------------------------------------Cattle booking create-------------------------------------
            foreach ($request->cattles as $cattleData) {



                $cattleBooking = new CattleBooking();
                $cattleBooking->cattle_id = $cattleData['cattle_id'];
                $cattleBooking->booking_id = $booking->id;
                $cattleBooking->sale_price = $cattleData['sale_price'];
                $cattleBooking->advance_price = $cattleData['advance_price'];
                $cattleBooking->payment_method = $request->payment_method;
                $cattleBooking->save();

                $cattle = Cattle::findOrFail($cattleData['cattle_id']);
                $cattle->status = 2;
                $cattle->save();
            }
            // -------------------------------------End Cattle booking create-------------------------------------


            // -------------------------------------Cattle booking payment-------------------------------------
            $bookingPayment = new BookingPayment;
            $bookingPayment->cattle_booking_id = $booking->id;
            $bookingPayment->price             = $totalAdvancePrice;
            $bookingPayment->save();
            // -------------------------------------End Cattle booking payment-------------------------------------


            // -------------------------------------Delivery location create-------------------------------------
            $deliveryLocation = new DeliveryLocation();
            $deliveryLocation->booking_id    = $booking->id;
            $deliveryLocation->district_city = $request->distric_city;
            $deliveryLocation->area          = $request->area_location;
            $deliveryLocation->status        = 0;
            $deliveryLocation->save();
            // -------------------------------------End Delivery location -------------------------------------


            DB::commit();
            $toast[] = ['success', 'Cattle booking created successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Cattle booking creation failed.'];
            return back()->withToasts($toast);
        }
    }

    function edit($id)
    {

        $pageTitle = 'Cattle Booking Edit';
        $booking   = Booking::with(['booking_payments', 'cattle_bookings', 'delivery_location'])->findOrFail($id);
        // dd($booking->due_price);

        $cattleIds = $booking->cattle_bookings->pluck('cattle_id');
        $cattles   = Cattle::with('lastCattleRecord', 'cattle_expenses')->whereIn('id', $cattleIds)->orWhere('status', 1)->get();

        $customers = Customer::orderBy('id', 'desc')->get();

        return view('admin.booking.edit', compact('pageTitle', 'cattles', 'customers', 'booking'));
    }



    // ================== Booking delete  start================= \\

    function delete($id)
    {
        $booking = Booking::with(['cattle_bookings', 'delivery_location'])->findOrFail($id);

        // dd($booking);

        if (!$booking) {
            $toast[] = ['error', 'Booking is not valid.'];
            return back()->withToasts($toast);
        }

        DB::transaction(function () use ($booking) {

            // Delete all cattle bookings (multiple rows)
            $booking->cattle_bookings()->delete();

            // Delete delivery location (single row)
            $booking->delivery_location()->delete();

            // Finally delete booking
            $booking->delete();
        });

        return back()->with('success', 'Booking and all related data deleted successfully!');
    }

    // ================== Booking delete  end ================= \\






    function update(Request $request, $id)
    {


        // dd("hello");
        // dd($request->all());
        $request->validate([
            'payment_method'          => ['required', 'string'],
            'delivery_date'           => ['required', 'date_format:d/m/Y'],
            'cattles'                 => ['required', 'array', 'min:1'],
            'cattles.*.cattle_id'     => ['required', 'integer', 'exists:cattles,id'],
            'cattles.*.sale_price'    => ['nullable', 'numeric', 'min:0'],
            'cattles.*.advance_price' => ['nullable', 'numeric', 'min:0', 'lte:cattles.*.sale_price'],
        ], [
            'delivery_date.date' => 'The delivery date must be a valid date.',
            'delivery_date.after_or_equal' => 'Delivery date cannot be in the past date',
        ]);

        $deliveryDate = Carbon::createFromFormat('d/m/Y', $request->input('delivery_date'));
        DB::beginTransaction();

        try {

            $booking = Booking::with('cattle_bookings', 'booking_payments')->findOrFail($id);

            $totalSalePrice = collect($request->cattles)->sum('sale_price');

            // -------------------------------------Booking create-------------------------------------
            $booking->payment_method = $request->payment_method;
            $booking->sale_price     = $totalSalePrice;
            $booking->delivery_date  = $deliveryDate->toDateTimeString();
            $booking->save();
            // -------------------------------------End booking create-------------------------------------


            $newCattleIds = collect($request->cattles)->pluck('cattle_id')->map(fn($id) => (int) $id)->toArray();
            // dd($newCattleIds, $request->all());

            $existingBookings = $booking->cattle_bookings()->get();

            $existingCattleIds = $existingBookings->pluck('cattle_id')->toArray();

            $toDelete = $existingBookings->whereNotIn('cattle_id', $newCattleIds);
            foreach ($toDelete as $bookingCattle) {
                Cattle::findOrFail($bookingCattle->cattle_id)->update(['status' => 1]);
                $bookingCattle->delete();
            }


            // Step 5: Insert new cattle if they don't exist in DB

            $TotalSalesPrice = 0;
            foreach ($request->cattles as $newCattle) {
                $TotalSalesPrice += (float)$newCattle['sale_price'] ?? 0;
                if (!in_array((int)$newCattle['cattle_id'], $existingCattleIds)) {
                    $cattleBooking = new CattleBooking();
                    $cattleBooking->cattle_id  = $newCattle['cattle_id'];
                    $cattleBooking->booking_id = $booking->id;
                    $cattleBooking->sale_price = (float)$newCattle['sale_price'] ?? 0;
                    $cattleBooking->save();

                    $cattle = Cattle::findOrFail($newCattle['cattle_id']);
                    $cattle->status = 2;
                    $cattle->save();
                } else {
                    $cattleBooking = CattleBooking::where('cattle_id', $newCattle['cattle_id'])->first();
                    // dd($cattleBooking);
                    $cattleBooking->sale_price = (float)$newCattle['sale_price'] ?? 0;
                    $cattleBooking->save();
                }
            }

            // dd($TotalSalesPrice);
            $booking->due_price = $TotalSalesPrice - $booking->total_payment_amount;
            $booking->save();


            // -------------------------------------Cattle booking payment-------------------------------------
            $bookingPayment = new BookingPayment;
            $bookingPayment->cattle_booking_id = $booking->id;
            $bookingPayment->price             = 0;
            $bookingPayment->save();
            // -------------------------------------End Cattle booking payment-------------------------------------







            DB::commit();
            $notifyAdd[] = ['success', "Cattle booking updated successfully"];
            return back()->withToasts($toast ?? $notifyAdd);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Cattle booking creation failed.'];
            return back()->withToasts($toast);
        }
    }


    public function remove($id)
    {
        $cattle = Cattle::with('cattle_images')->find($id);

        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }

        DB::beginTransaction();
        try {
            if ($cattle->cattle_images->count() > 0) {
                foreach ($cattle->cattle_images as $image) {
                    $filePath = getFilePath('cattle') . '/' . $image->image_path;

                    // Check if file exists before deleting
                    if (!empty($image->image_path) && FileFacades::exists($filePath)) {
                        $fileDeleted = fileManager()->removeFile($filePath);
                    } else {
                        Log::warning('File not found: ' . $filePath);
                    }

                    $image->delete();
                }
            }

            $cattle->delete();
            DB::commit();

            $toast[] = ['success', 'Cattle deleted successfully.'];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Cattle deletion failed: ' . $th->getMessage());
            $toast[] = ['error', 'Something went wrong! Cattle deletion failed.'];
        }

        return back()->withToasts($toast);
    }

    function view($id)
    {
        $pageTitle = 'Booking Cattles';

        $booking = Booking::with([
            'customer',
            'cattle_bookings',
            'cattle_bookings.cattle',
            'cattle_bookings.cattle.primaryImage'
        ])
            ->searchable(['cattle:name', 'cattle:tag_number', 'customer:first_name', 'booking_number'])
            ->where('id', $id)
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->first();

        // dd($booking->customer->fullname);
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.booking.booking_number_view', compact('pageTitle', 'customers', 'booking'));
    }

    public function bookingNumberSearch(Request $request)
    {
        $search = $request->get('search');
        $results = Booking::where('booking_number', 'like', "%$search%")
            ->distinct()
            ->limit(15)
            ->get(['booking_number']);

        return response()->json([
            'data' => $results
        ]);
    }

    public function bookingNumberByCustomerSearch(Request $request)
    {
        $bookingNumber = $request->input('booking_number');

        $booking = Booking::with(['customer', 'cattle', 'cattle.primaryImage'])
            ->whereRaw('LOWER(booking_number) = ?', [strtolower($bookingNumber)])
            ->orderBy('id', 'desc')
            ->first();

        if ($booking && $booking->customer) {
            return response()->json([
                'customer_exists' => true,
                'customer_id' => $booking->customer->id,
            ]);
        } else {
            return response()->json([
                'customer_exists' => false,
            ]);
        }
    }

    public function estimateCostAndWeightOnDelivery(Request  $request)
    {
        $cattle = Cattle::with('lastCattleRecord', 'cattle_expenses', 'cattleCategory')->where('id', $request->id)->first();
        $medicine = 0;
        if (
            $cattle->type == ManageStatus::PURCHASE_CATTLE ||
            ($cattle->type == ManageStatus::BORN_CATTLE && Carbon::parse($cattle->purchase_date)->addYear()->lte(now()))
        ) {
            $validFromDate = Carbon::parse($cattle->lastCattleRecord->valid_from_date);
            $deliveryDate  = Carbon::createFromFormat('d/m/Y', $request->input('deliveryDate'));

            $dayDiff = 1 + $validFromDate->diffInDays($deliveryDate);
            // dd($validFromDate, $deliveryDate, $dayDiff);
            $growthWeight   = $cattle->lastCattleRecord->growth_weight ?? 0;
            $latestWeight   = $cattle->lastCattleRecord->last_updated_weight ?? 0;
            $ratioWeight    = $cattle->lastCattleRecord->weight_for_price ?? 1;
            $ratioPrice     = $cattle->lastCattleRecord->price_for_weight ?? 0;
            $purchasePrice  = $cattle->purchase_price ?? 0;
            $dailyTotalExpense = $cattle->total_cost ?? 0;

            $totalGrowthWeight  = $dayDiff * $growthWeight;
            $totalUpdatedWeight = $totalGrowthWeight + $latestWeight;

            $totalRatioVal = ($totalUpdatedWeight / $ratioWeight) * $ratioPrice;
            $dailyTotalExpense = $totalRatioVal * $dayDiff;

            if ($cattle->cattleCategory->cattle_group == ManageStatus::CATTLE_CATEGORY_COW_GROUP) {
                $genTotalExpence = GenTotalExpense::whereIn('expens_type', [3, 4])->sum('per_cattle_expense');
            } else {
                $genTotalExpence = 500;
                $medicine = 500;
            }

            // Final calculation
            $grandTotal = $purchasePrice + $dailyTotalExpense + $genTotalExpence + $medicine;

            // Final result
            $totalEtimateCostOnDelivery = round($grandTotal, 2);
            $totalEtimateWeight         = round($totalUpdatedWeight, 2);
        } else {
            $totalEtimateCostOnDelivery = 0;
            $totalEtimateWeight = 0;
        }

        if ($totalEtimateCostOnDelivery) {
            return response()->json([
                'status' => true,
                'totalEtimateCostOnDelivery' => $totalEtimateCostOnDelivery,
                'totalEtimateWeight' => $totalEtimateWeight,
                'cattleType' => $cattle->type,
                'notMature' => 1,
            ]);
        } else {
            return response()->json([
                'status' => true,
                'totalEtimateCostOnDelivery' => $totalEtimateCostOnDelivery,
                'totalEtimateWeight' => $totalEtimateWeight,
                'cattleType' => $cattle->type,
                'notMature' => 2,
            ]);
        }
    }


    public function paymentList($id)
    {
        $booking = Booking::findOrFail($id);

        $pageTitle = 'Payment List' . ' (' . $booking->booking_number . ")";
        $bookingPayments = BookingPayment::where('cattle_booking_id', $booking->id)
            ->with('booking.cattle_bookings.cattle')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate(30));
        $is_printed = PaymentReceipt::where('booking_id', $booking->id)->first() ? 'yes' : 'no';
        $paymentBooking = CattleBooking::where('booking_id',  $booking->id)->with(['cattle:id,tag_number', 'bookingPayment'])->paginate(getPaginate(30));
        //  dd($paymentBooking);
        // dd($paymentBooking);


        return view('admin.booking_payment.index', compact('pageTitle', 'bookingPayments', 'booking', 'is_printed', 'paymentBooking'));
    }

    public function allPayments()
    {
        $pageTitle = 'All Payments';
        $allPayments = BookingPayment::with('booking')
            ->orderBy('id', 'desc')
            ->get();

        $bookingPayments = $allPayments->groupBy(function($item) {
            return $item->booking->booking_number ?? 'N/A';
        });

        return view('admin.booking_payment.allbookingpayment', compact('pageTitle', 'bookingPayments'));
    }










    public function addPayment($id)
    {
        $pageTitle = 'Add Payment';
        $booking = Booking::with([
            'cattle_bookings.cattle:id,tag_number,name'
        ])->findOrFail($id);



        // dd($booking);
        return view('admin.booking_payment.create', compact('pageTitle', 'booking'));
    }

    public function storePayment(Request $request)
    {
        // dd($request->all()); 
        try {
            DB::beginTransaction();

            $request->validate([
                'booking_id'          => ['required', 'integer', 'exists:bookings,id'],
                'cattle_booking_ids'  => ['required', 'array'],
                'payment_method'      => ['required'],
                'amount'              => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
                'payment_date'        => ['required', 'date_format:d/m/Y'],
            ]);

            $booking = Booking::findOrFail($request->booking_id);

            $newTotalPayment = $booking->total_payment_amount + $request->amount;

            if ($newTotalPayment > $booking->sale_price) {
                return back()->withErrors([
                    'amount' => 'Payment exceeds due amount'
                ]);
            }

            // Cattle tag numbers
            $cattles = Cattle::whereIn('id', $request->cattle_booking_ids)
                ->select('tag_number')
                ->get();

            $tagNumberString = $cattles->pluck('tag_number')->implode('/');

            $paymentDate = Carbon::createFromFormat('d/m/Y', $request->payment_date);

            // Booking Payment
            $bookingPayment = new BookingPayment();
            $bookingPayment->cattle_booking_id = $booking->id;
            $bookingPayment->price            = $request->amount;
            $bookingPayment->cattle_name      = $tagNumberString;
            $bookingPayment->payment_date     = $paymentDate->toDateString();
            $bookingPayment->save();

            // Update booking summary
            $booking->total_payment_amount = $newTotalPayment;
            $booking->due_price            = $booking->sale_price - $newTotalPayment;
            $booking->save();

            // Cattle Booking payment log
            $cattleBooking = new CattleBooking();
            $cattleBooking->booking_id     = $booking->id;
            $cattleBooking->payment        = $request->amount;
            $cattleBooking->cattle_name    = $tagNumberString;
            $cattleBooking->payment_method = $request->payment_method;
            $cattleBooking->booking_payment_id = $bookingPayment->id;

            $cattleBooking->save();

            DB::commit();

            return back()->withToasts([
                ['success', 'Booking Payment created successfully']
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            // optional: log error for debugging
            Log::error('Booking Payment Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()->withErrors([
                'error' => 'Something went wrong! Please try again.'
            ]);
        }
    }


    // ================================payment edit =========================//
    function editPayment($booking, $payment)
    {

        // dd($booking, $payment);

        $pageTitle = 'Edit Payment';
        $BookingPayment = CattleBooking::findOrFail($payment);
        // dd($BookingPayment);

        // $booking = Booking::findOrFail($booking);
        $booking = Booking::with([
            'cattle_bookings.cattle:id,tag_number,name'
        ])->findOrFail($booking);
        // dd($booking);
        return view('admin.booking_payment.edit', compact('pageTitle', 'BookingPayment', 'booking'));
    }






    // ====================================payment update====================//

    // function updatePayment(Request $request)
    // {

    //     $request->validate([
    //         'booking_id'   => ['required', 'integer', 'exists:bookings,id'],
    //         'payment_id'   => ['required', 'integer', 'exists:booking_payments,id'],
    //         'amount'       => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
    //         'payment_date' => ['required'],
    //         'cattle_name'  => ['required', 'string', 'max:255'],
    //     ]);

    //     $booking = Booking::findOrFail($request->booking_id);
    //     $payment = BookingPayment::findOrFail($request->payment_id);


    //     $adjustedTotalPayment =
    //         $booking->total_payment_amount
    //         - $payment->price
    //         + $request->amount;


    //     if ($adjustedTotalPayment > $booking->sale_price) {
    //         return back()->withErrors([
    //             'amount' => 'Payment exceeds due amount'
    //         ]);
    //     }

    //     // Format payment date
    //     $paymentDate = Carbon::createFromFormat('d/m/Y', $request->payment_date);

    //     // Update payment
    //     $payment->price = $request->amount;
    //     $payment->cattle_name = $request->cattle_name;
    //     $payment->payment_date = $paymentDate->toDateString();
    //     $payment->save();
    //     // Update booking summary
    //     $booking->total_payment_amount = $adjustedTotalPayment;
    //     $booking->due_price = $booking->sale_price - $adjustedTotalPayment;
    //     $booking->save();

    //     $notify[] = ['success', 'Booking Payment updated successfully'];
    //     return redirect()->back()->withToasts($notify);
    // }





public function updatePayment(Request $request)
{
   


// dd($request->all());
    try {
        DB::beginTransaction();

        $request->validate([
            'booking_id'          => ['required', 'integer', 'exists:bookings,id'],
            'cattle_booking_ids'  => ['required', 'array'],
            'payment_method'      => ['required'],
            'amount'              => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
            'payment_date'        => ['required', 'date_format:d/m/Y'],
        ]);
// dd($request->all()); 
    $cattleBooking = CattleBooking::findOrFail($request->payment_id);
// dd($booking_id);


        $bookingPayment = BookingPayment::findOrFail($cattleBooking->booking_payment_id);
        // dd($bookingPayment);
        $booking = Booking::findOrFail($request->booking_id);

        $oldAmount = $bookingPayment->price;
        $newAmount = $request->amount;

        $newTotalPayment = ($booking->total_payment_amount - $oldAmount) + $newAmount;

        if ($newTotalPayment > $booking->sale_price) {
            return back()->withErrors([
                'amount' => 'Payment exceeds due amount'
            ]);
        }

        $cattles = Cattle::whereIn('id', $request->cattle_booking_ids)
            ->select('tag_number')
            ->get();

        $tagNumberString = $cattles->pluck('tag_number')->implode('/');

        $paymentDate = Carbon::createFromFormat('d/m/Y', $request->payment_date);

        $bookingPayment->price        = $newAmount;
        $bookingPayment->cattle_name  = $tagNumberString;
        $bookingPayment->payment_date = $paymentDate->toDateString();
        $bookingPayment->save();

        $booking->total_payment_amount = $newTotalPayment;
        $booking->due_price = $booking->sale_price - $newTotalPayment;
        $booking->save();

    

        if ($cattleBooking) {
            $cattleBooking->payment        = $newAmount;
            $cattleBooking->cattle_name    = $tagNumberString;
             $cattleBooking->payment        = $request->amount;
            $cattleBooking->payment_method = $request->payment_method;
            $cattleBooking->save();
        }

        DB::commit();

        return back()->withToasts([
            ['success', 'Booking Payment updated successfully']
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('Booking Payment Update Error', [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);

        return back()->withErrors([
            'error' => 'Something went wrong! Please try again.'
        ]);
    }
}




    public function refundPayment($id)
    {
        $pageTitle = 'Refund Payment';
        $booking = Booking::findOrFail($id);

        return view('admin.booking_payment.refund', compact('pageTitle', 'booking'));
    }

    public function refundPaymentStore(Request $request)
    {
        $pageTitle = 'Refund Payment';
        $request->validate([
            'booking_id'     => ['required', 'integer', 'exists:bookings,id'],
        ]);
        $booking = Booking::findOrFail($request->booking_id);
        if ($booking->total_payment_amount <= $booking->sale_price) {
            $notifyAdd[] = ['error', "Booking Payment not refundable"];
            return back()->withToasts($notifyAdd);
        }
        $booking->total_payment_amount  = $booking->sale_price;
        $booking->save();
        $notifyAdd[] = ['success', "Booking Payment refund successfully"];
        return back()->withToasts($notifyAdd);
    }

    // ================== Delivery Section ================= \\

    function delivery()
    {
        $pageTitle = 'Delivery List';
        $deliveries = DeliveryLocation::with('booking')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.delivery.index', compact('pageTitle', 'deliveries'));
    }

    function deliveryEdit($id)
    {
        $this->authorizeForAdmin('has-permission', 'delivery edit');
        $pageTitle = 'Edit Delivery';
        $delivery  = DeliveryLocation::with('booking')->findOrFail($id);
        return view('admin.delivery.edit', compact('pageTitle', 'delivery'));
    }

    function deliveryUpdate(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'delivery edit');

        $request->validate([
            'district_city'  => ['required', 'string'],
            'area'           => ['required', 'string'],
        ], [
            'district_city' => 'The delivery district or city not null.',
            'area' => 'Delivery must not be null',
        ]);

        DB::beginTransaction();

        try {

            $delivery = DeliveryLocation::findOrFail($id);

            // -------------------------------------Booking create-------------------------------------
            $delivery->district_city = $request->district_city;
            $delivery->area     = $request->area;
            $delivery->save();
            // -------------------------------------End booking create-------------------------------------




            DB::commit();
            $notifyAdd[] = ['success', "Delivery location updated successfully"];
            return back()->withToasts($toast ?? $notifyAdd);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Delivery location updated failed.'];
            return back()->withToasts($toast);
        }
    }

    public function printChallan($id)
    {
        $booking = Booking::with(['booking_payments', 'customer', 'cattle_bookings.cattle', 'delivery_location'])->findOrFail($id);
        $pageTitle = 'Print Booking Challan of ' . $booking->booking_number;
        // dd($booking);

        return view('admin.delivery.challan_print', compact('pageTitle', 'booking'));
        // $cattles = Cattle::where('cattleCategory');
        // $booking->status = ManageStatus::BOOKING_DELIVERED;
        // $booking->save();
        // $notifyAdd[] = ['success', "Cattle booking delivered successfully"];
        return back()->withToasts($toast ?? $notifyAdd);
    }


    // ============================ store cattle which cattle is printed===================================//
    public function Print_cattle(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'selected_cattles' => 'required|array|min:1',
            'booking_id' => 'required',
            'customer_id' => 'required',
        ]);




        DB::beginTransaction();

        try {


            $lastPrint = BookingPrints::orderBy('id', 'desc')->first();

            if ($lastPrint) {
                $lastNumber = (int) str_replace('PRINT-', '', $lastPrint->print_uid);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }


            $newPrintUid = 'PRINT-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

            // dd( $newPrintUid);
            $bookingPrint = BookingPrints::create([
                'booking_id'  => $request->booking_id,
                'customer_id' => $request->customer_id,
                'print_uid'   => $newPrintUid,
                'is_print'    => 'yes',
                'printed_at'  => now(),
            ]);

            // 🔹 Optional: selected cattle গুলো update
            // CattleBooking::whereIn('id', $request->selected_cattles)
            //     ->update(['print_uid' => $newPrintUid]);
            $bookingPrint->cattles()->attach($request->selected_cattles);
            //   dd('hello');


            $deleveryDetails = booking::with(['customer', 'delivery_location'])->find($request->booking_id);

            // dd($deleveryDetails);
            $PrintsDatas = BookingPrints::where('id', $bookingPrint->id)
                ->with(['booking.delivery_location', 'customer', 'cattles'])
                ->first();




            // dd($PrintsDatas);


            DB::commit();

            // return view('report.gatePass', compact('PrintsDatas', 'deleveryDetails'));

            $pdf = Pdf::loadView('report.gatePass', [
                'PrintsDatas' => $PrintsDatas
            ]);

            return $pdf->stream('gatePass.pdf');
        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }










    public function challanPrinted(Request $request, $id)
    {
        $booking = Booking::with(['booking_payments', 'cattle_bookings', 'delivery_location'])->findOrFail($id);
        // $pageTitle = 'Print Booking Challan of ' . $booking->booking_number;
        // dd($booking);

        // return view('admin.delivery.challan_print', compact('pageTitle', 'booking'));
        // $cattles = Cattle::where('cattleCategory');
        $booking->status = ManageStatus::BOOKING_CHALLAN_PRINT;
        $booking->save();
        $notifyAdd[] = ['success', "Booking challan print successfully"];
        return back()->withToasts($toast ?? $notifyAdd);
    }

    public function cattleDelivered($id)
    {
        $booking = Booking::with(['booking_payments', 'cattle_bookings', 'delivery_location'])->findOrFail($id);
        $cattles = Cattle::whereIn('status', [ManageStatus::CATTLE_BOOKED, ManageStatus::CATTLE_ACTIVE])
            ->whereHas('cattleCategory', function ($query) {
                $query->where('cattle_group', ManageStatus::CATTLE_CATEGORY_COW_GROUP);
            })
            ->get();

        $genExpense = GenTotalExpense::where('expens_type', ManageStatus::GEN_EXPENSE)->first();
        $medExpense = GenTotalExpense::where('expens_type', ManageStatus::MEDICINE)->first();

        foreach ($booking->cattle_bookings as $bookedCattle) {
            $cattle = Cattle::with('cattleCategory')->findOrFail($bookedCattle->cattle_id);
            if ($cattle->status == ManageStatus::CATTLE_BOOKED) {
                if ($cattle->cattleCategory->cattle_group == ManageStatus::CATTLE_CATEGORY_GOAT_GROUP) {
                    if ($genExpense->total_amount > 500) {
                        $genExpense->total_amount -= 500;
                        $cattle->total_gen_exp = 500;
                        $genExpense->per_cattle_expense = $genExpense->total_amount / $cattles->count();
                        $genExpense->save();
                    }
                    if ($medExpense->total_amount > 500) {
                        $medExpense->total_amount -= 500;
                        $cattle->total_med_exp = 500;
                        $medExpense->per_cattle_expense = $medExpense->total_amount / $cattles->count();
                        $medExpense->save();
                    }
                } else {
                    // dd($cattle);
                    if ($genExpense->total_amount > $genExpense->per_cattle_expense) {
                        $genExpense->total_amount -= $genExpense->per_cattle_expense;

                        $cattle->total_gen_exp = $genExpense->per_cattle_expense;
                        $genExpense->save();
                    }
                    if ($medExpense->total_amount > $medExpense->per_cattle_expense) {
                        $medExpense->total_amount -= $medExpense->per_cattle_expense;
                        $cattle->total_med_exp = $medExpense->per_cattle_expense;
                        $medExpense->save();
                    }
                }
                $cattle->status = ManageStatus::CATTLE_DELIVERED;
                $cattle->save();
            }
        }
        $booking->status = ManageStatus::BOOKING_DELIVERED;
        $booking->save();
        $notifyAdd[] = ['success', "Cattle booking delivered successfully"];
        return back()->withToasts($toast ?? $notifyAdd);
    }


    // ================================Payment Slip Start===================================//



    function paymentSlip(Request $request, $id)
    {


        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            // 'cattle_display_name' => 'required',
        ]);

        //   dd($request->all());

        // dd($request->all());

        DB::beginTransaction();
        try {
            // Generate unique payment UID
            $UniqueID = uniqueId(PaymentReceipt::class, 'REC-', 6);
            //  dd($UniqueID);

            // Get booking payment data
            $receptPayment = BookingPayment::with(['booking.delivery_location', 'booking.customer'])
                ->findOrFail($request->booking_id);
            // dd($receptPayment);

            $total_received = BookingPayment::where('cattle_booking_id', $request->booking_id)
                ->sum('price');




            $payment_price  = CattleBooking::find($request->payment_id);
            // dd($payment_price);

            $payment_receipt_price = 0;

            if ($payment_price->payment === null) {

                $payment_receipt_price = $payment_price->advance_price;
            } else if ($payment_price->advance_price === null) {
                $payment_receipt_price = $payment_price->payment;
            }
            // dd($payment_receipt_price);

            $paymentReceipt = PaymentReceipt::updateOrCreate(

                [

                    'booking_id' => $request->booking_id,
                    'cattle_booking_id' => $id,

                ],

                [

                    'payment_uid' => $UniqueID,

                    'receipt_tk'  =>   $payment_receipt_price,

                    'cattle_booking_id' => $id,

                    'comment'     => $request->comment ?? $receptPayment->cattle_name,
                    'printed_at'  => $request->printed_at ?? now(),


                ]

            );

            //  dd($UniqueID);


            DB::commit();

            // PHP-only number to words
            $inword = takaInWords($paymentReceipt->receipt_tk);

            $paymentReceiptsData = PaymentReceipt::with(['booking.customer', 'booking.delivery_location', 'booking.cattle'])->find($paymentReceipt->id);
            // dd( $paymentReceiptsData);
            // Generate PDF
            $pdf = Pdf::loadView('report.paymentReceipt', [
                'paymentReceiptsData' => $paymentReceiptsData,
                'inword' => $inword,
                'total_received' => $total_received,
            ]);

            return $pdf->stream('payment_receipt.pdf');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }







    // ================================Payment Slip End===================================//




    // =====================================Booking Cancel======================================//


    public function BookingCancel(Request $request)
    {
        $bookingId = $request->input('booking_id');

        // Booking find
        $booking = Booking::findOrFail($bookingId);

        // Check if already delivered (status = 2)
        if ($booking->status === 2) {
            return response()->json([
                'success' => true,
                'message' => 'Booking cannot be cancelled, it is already delivered.',
                'status' => $booking->status,
            ]);
        } else {
            // Update booking status to 'cancel'
            $booking->booking_status = 'cancel';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.',
                'status' => $booking->booking_status,
            ]);
        }
    }




    public function undoBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Only allow undo if currently cancelled
        if ($booking->booking_status != 'cancel') {
            return response()->json([
                'success' => false,
                'message' => 'Booking is not cancelled.',
            ]);
        }

        $booking->booking_status = 'active'; // undo cancel
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking status changed to Active.',
            'status' => $booking->booking_status,
        ]);
    }
}
