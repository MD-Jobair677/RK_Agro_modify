<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CattleBooking;
use App\Models\BookingPayment;
use App\Http\Controllers\Controller;

class BookingPaymentController extends Controller
{
    function index($id)
    {
        $pageTitle = 'Booking Payments List';
        $cattleBooking = CattleBooking::findOrFail($id);
        // dd($cattleBooking);
        $bookingPayments = BookingPayment::with(['cattleBooking', 'cattleBooking.cattle'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->where('cattle_booking_id', $id)
            ->latest()
            ->paginate(getPaginate());

        return view('admin.cattle_payment.index', compact('pageTitle', 'bookingPayments','cattleBooking'));
    }

    function create($id)
    {
        $pageTitle = 'Payment Create';
        $cattleBooking = CattleBooking::with(['booking_payments'])->findOrFail($id);
        return view('admin.cattle_payment.create', compact('pageTitle', 'cattleBooking'));
    }

    function store(Request $request,$id)
    {
        $pageTitle = 'Cattle Booking Create';
        $cattleBooking = CattleBooking::with(['booking_payments'])->findOrFail($id);
        $request->validate([
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
        $alreadyBooking = $cattleBooking->booking_payments->sum('price');

        if ($cattleBooking->sale_price  < ($alreadyBooking + $request->price)) {
            $toast[] = ['error', 'Payment price must not be greater than sale price.'];
            return back()->withToasts($toast);
        }

        try {
            // -------------------------------------Create Booking Payments-------------------------------------
            $bookingPayment = new BookingPayment();
            $bookingPayment->cattle_booking_id = $cattleBooking->id;
            $bookingPayment->price = $request->price;
            $bookingPayment->save();

            $toast[] = ['success', 'Cattle booking created successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            $toast[] = ['error', 'Something went wrong! Cattle booking creation failed.'];
            return back()->withToasts($toast);
        }
    }

}
