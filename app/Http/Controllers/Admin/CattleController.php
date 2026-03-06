<?php

namespace App\Http\Controllers\Admin;

use HTMLPurifier;
use Carbon\Carbon;
use App\Jobs\TestJob;
use App\Models\Cattle;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\CattleImage;
use App\Models\CattleRecord;
use Illuminate\Http\Request;
use App\Models\CattleCategory;
use App\Models\GeneralExpense;
use App\Constants\ManageStatus;
use App\Models\GenTotalExpense;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\File as FileFacades;

class CattleController extends Controller
{
    function index()
    {

        $pageTitle = 'Cattle List';
        $cattles = Cattle::with(['cattleCategory', 'primaryImage'])
            ->searchable(['tag_number'])
            ->dateFilter()
            ->orderBy('id')
            ->latest()
            ->paginate(getPaginate(50));
        return view('admin.cattle.index', compact('pageTitle', 'cattles'));
    }

    function create()
    {
        $pageTitle = 'Cattle Create';
        $categories = Category::where('status', 1)->get();
        $suppliers = Supplier::orderBy('id')->where('supplier_type', ManageStatus::CATTLE)->where('status', 1)->latest()->get();
        $cattleCategories = CattleCategory::where('status', 1)->get();
        return view('admin.cattle.create', compact('pageTitle', 'categories', 'cattleCategories', 'suppliers'));
    }

    function store(Request $request)
    {

        $pageTitle = 'Cattle Create';
        $request->validate([
            'supplier_id'     => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'new_supplier' && !\App\Models\Supplier::where('id', $value)->exists()) {
                        $fail('The selected supplier is invalid.');
                    }
                },
            ],
            'category_id'        => ['required_if:supplier_id,new_supplier', 'numeric', 'exists:categories,id',],
            'sup_name'           => 'required_if:supplier_id,new_supplier',
            'contact_number'     => 'required_if:supplier_id,new_supplier',
            'string',
            'sup_address'        => 'nullable',
            'name'               => ['nullable', 'string'],
            'category_id'        => ['required', 'numeric', "exists:categories,id"],
            'cattle_category_id' => ['required', 'numeric', "exists:cattle_categories,id"],
            'tag_number'         => ['required', 'string', 'unique:cattles,tag_number'],
            'purchase_date'      => ['required', 'date_format:d/m/Y', 'before_or_equal:today'],
            'purchase_weight'    => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,3})?$/'],
            'purchase_price'     => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'row_number'         => ['nullable', 'string'],
            'stall_number'       => ['nullable', 'string'],
            'breed'              => ['nullable', 'string'],
            'gender'             => ['required', 'in:Male,Female,Other,Unknown'],
            'description'        => ['nullable', 'string'],
            'price_for_weight'   => ['required', 'numeric', 'min:0.1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'weight_for_price'   => ['required', 'numeric', 'min:0.1', 'regex:/^\d+(\.\d{1,3})?$/'],
            'growth_weight'      => ['required', 'decimal:0,3', 'min:0.001'],
            'images'             => ['nullable', 'array', 'min:0'],
            'images.*'           => ['nullable', File::types(['png', 'jpg', 'jpeg', 'PNG', 'JPG', 'JPEG'])],
            'type'               => ['required', 'numeric', 'in:1,2'],


        ], [

            'purchase_date.date'            => 'The purchase date must be a valid date.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future date',
            'purchase_weight.numeric'       => 'The purchase weight must be a number.',
            'purchase_weight.min'           => 'The purchase weight must be at least 1 kg.',
            'purchase_weight.regex'         => 'The purchase weight must have up to 2 decimal places.',
            'gender.in'                     => 'The gender must be one of the following: Male, Female, Other, Unknown.',
            'images.min'                    => 'The images field must have at least 1 item.',

        ]);

        DB::beginTransaction();

        try {
            // Convert the custom formatted date to timestamp
            $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->input('purchase_date'));
            $purifier  = new HTMLPurifier();

            if ($request->supplier_id === 'new_supplier') {
                $supplier = new Supplier();
                $supplier->category_id    = $request->category_id;
                $supplier->first_name           = $request->sup_name;
                $supplier->contact_number = $request->contact_number;
                $supplier->supplier_type  = 2;
                $supplier->address        = $purifier->purify($request->sup_address);
                $supplier->save();
            }

            $cattle = new Cattle();
            $cattle->name               = $request->name;
            $cattle->supplier_id        = isset($supplier) ? $supplier->id : $request->supplier_id;
            $cattle->cattle_category_id = $request->cattle_category_id;
            $cattle->tag_number         = $request->tag_number;
            $cattle->type               = $request->type;
            $cattle->purchase_date      = $purchaseDate->toDateTimeString();
            $cattle->purchase_price     = $request->purchase_price;
            $cattle->purchase_weight    = $request->purchase_weight;
            $cattle->row_number         = $request->row_number;
            $cattle->stall_number       = $request->stall_number;
            $cattle->breed              = $request->breed;
            $cattle->gender             = $request->gender;
            $cattle->status             = 1;
            $cattle->description        = $purifier->purify($request->description);
            $cattle->save();

            // ------------------------------opening cattle record create----------------------------------
            $cattleRecord = new CattleRecord();
            $cattleRecord->cattle_id           = $cattle->id;
            $cattleRecord->purchase_weight     = $request->purchase_weight;
            $cattleRecord->price_for_weight    = $request->price_for_weight;
            $cattleRecord->weight_for_price    = $request->weight_for_price;
            $cattleRecord->purchase_date       = $purchaseDate->toDateTimeString();
            $cattleRecord->growth_weight       = $request->growth_weight;
            $cattleRecord->valid_from_date     = $purchaseDate->toDateTimeString();
            $cattleRecord->last_updated_weight = $request->purchase_weight;
            $cattleRecord->is_opening          = 1;
            $cattleRecord->save();

            // -------------------------------------multiple image add-------------------------------------
            if ($request->hasFile('images')) {
                foreach ($request->images as $img) {
                    $cattle_images = new CattleImage();
                    $cattle_images->cattle_id = $cattle->id;
                    $cattle_images->image_path = fileUploader($img, getFilePath('cattle'));
                    $cattle_images->save();
                }
            }

            // -------------------------------------Cattle expense-------------------------------------
            $genExpense = new GeneralExpense();
            $genExpense->expense_type  = ManageStatus::CATTLE;
            $genExpense->expense_date  = $purchaseDate->toDateTimeString();
            $genExpense->cost_amount   = $request->purchase_price;
            $genExpense->purpose       = 'Expense for purchase cattle of ' .$request->name.'/'. $request->tag_number;
            $genExpense->note          = $purifier->purify($request->description);
            $genExpense->save();


            $this->generalExpenseDistribute($request, $cattle);

            DB::commit();
            $toast[] = ['success', 'Cattle created successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Cattle creation failed.'];
            return back()->withToasts($toast);
        }
    }

    function edit($id)
    {
        $cattleCategories = CattleCategory::where('status', 1)->get();
        $cattle = Cattle::with('cattle_images', 'primaryCattleRecord')->find($id);
        $pageTitle = $cattle->name . " Cattle Edit";
        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }

        return view('admin.cattle.edit', compact('pageTitle', 'cattleCategories', 'cattle'));
    }

    function update(Request $request, $id)
    {
        $pageTitle = 'Cattle Update';
        $cattle = Cattle::with('cattle_images', 'primaryCattleRecord')->find($id);
        $hasExistingImages = $cattle->cattle_images()->exists();

        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }

        $request->validate([
            'name'                 => ['nullable', 'string'],
            'cattle_category_id'   => ['required', 'numeric', "exists:cattle_categories,id"],
            'tag_number'           => ['required', 'string', Rule::unique('cattles', 'tag_number')->ignore($id)],
            'row_number'           => ['nullable', 'string'],
            'stall_number'         => ['nullable', 'string'],
            'breed'                => ['nullable', 'string'],
            'gender'               => ['required', 'in:Male,Female,Other,Unknown'],
            'description'          => ['nullable', 'string'],
            'images'               => ['nullable', 'array'],
            'images.*'             => ['nullable', File::types(['png', 'jpg', 'jpeg', 'PNG', 'JPG', 'JPEG'])],
        ], [
            'gender.in' => 'The gender must be one of the following: Male, Female, Other, Unknown.',

        ]);

        DB::beginTransaction();
        try {
            $cattle->name               = $request->name;
            $cattle->cattle_category_id = $request->cattle_category_id;
            $cattle->tag_number         = $request->tag_number;
            $cattle->row_number         = $request->row_number;
            $cattle->stall_number       = $request->stall_number;
            $cattle->breed              = $request->breed;
            $cattle->gender             = $request->gender;
            $cattle->description        = $request->description;
            if (in_array($request->status, [1, 2, 3, 4])) {
                $cattle->status = $request->status ? $request->status : $cattle->status;
            }
            $cattle->save();

            // -------------------------------------multiple image add-------------------------------------
            if ($request->hasFile('images')) {
                foreach ($request->images as $img) {
                    $cattle_images             = new CattleImage();
                    $cattle_images->cattle_id  = $cattle->id;
                    $cattle_images->image_path = fileUploader($img, getFilePath('cattle'));
                    $cattle_images->save();
                }
            }

            if ($request->status == 4 && $request->cattle_category_id) {

                $this->deadGenTotalExpense($cattle);
            }

            DB::commit();
            $toast[] = ['success', 'Cattle updated successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Cattle creation failed.'];
            return back()->withToasts($toast);
        }
    }

    function details($id)
    {
        $cattle                   = Cattle::with('cattle_images', 'lastCattleRecord', 'cattleCategory')->find($id);
        $cattleCategories         = CattleCategory::where('status', 1)->get();
        $specificGenTotalExpenses = GenTotalExpense::whereIn('expens_type', [ManageStatus::GEN_EXPENSE, ManageStatus::MEDICINE,])->get();
        $pageTitle                = $cattle->tag_number  . " Cattle Details";
        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }
        return view('admin.cattle.detail', compact('pageTitle', 'cattle', 'cattleCategories', 'specificGenTotalExpenses'));
    }

    function updateAskPrice(Request $request, $id)
    {
        $cattle = Cattle::find($id);

        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }

        // dd($request->all());
        $request->validate([
            'ask_price'    => ['required', 'decimal:2'],
        ], [
            'ask_price.in' => 'The asking price must be value.',

        ]);

        DB::beginTransaction();
        try {
            $cattle->asking_price = $request->ask_price;
            $cattle->save();

            // -------------------------------------multiple image add-------------------------------------

            DB::commit();
            $toast[] = ['success', 'Cattle asking price updated successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! update failed.'];
            return back()->withToasts($toast);
        }
    }


    function editWeight($id)
    {
        $cattle     = Cattle::with('cattle_images', 'lastCattleRecord')->find($id);
        $pageTitle  = $cattle->name . " Cattle Edit";

        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }
        $hasOneYearPassed = false;
        if ($cattle->type === 2 && $cattle->purchase_date) {
            $afterOneYear = Carbon::parse($cattle->purchase_date)->addYear();
            if ($afterOneYear <= now()) {
                $hasOneYearPassed = true;
            }
        }
        return view('admin.cattle.weight_edit', compact('pageTitle',  'cattle', 'hasOneYearPassed'));
    }


    function updateWeight(Request $request, $id)
    {

        $pageTitle = 'Cattle Update';
        $cattle = Cattle::with('cattle_images', 'lastCattleRecord')->find($id);
        if (!$cattle) {
            $toast[] = ['error', 'Cattle is not valid.'];
            return back()->withToasts($toast);
        }

        // check new born cattle after one year
        if ($cattle->type === 2 && $cattle->purchase_date) {
            $afterOneYear = Carbon::parse($cattle->purchase_date)->addYear();
            if ($afterOneYear >= now()) {
                $toast[] = ['error', "You can't change for new born cattle after one year"];
                return back()->withToasts($toast);
            }
        }

        $request->validate([
            'purchase_date'     => ['required', 'date_format:d/m/Y', 'before_or_equal:today'],
            'purchase_weight'   => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,3})?$/'],
            'purchase_price'    => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'price_for_weight'  => ['required', 'numeric', 'min:0.1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'weight_for_price'  => ['required', 'numeric', 'min:0.1', 'regex:/^\d+(\.\d{1,3})?$/'],
            'growth_weight'     => ['required', 'decimal:0,3', 'min:0.001'],

        ], [

            'purchase_date.date'            => 'The purchase date must be a valid date.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future date',
            'purchase_weight.numeric'       => 'The purchase weight must be a number.',
            'purchase_weight.min'           => 'The purchase weight must be at least 1 kg.',
            'purchase_weight.regex'         => 'The purchase weight must have up to 2 decimal places.',
            'gender.in'                     => 'The gender must be one of the following: Male, Female, Other, Unknown.',
            'images.min'                    => 'The images field must have at least 1 item.',
        ]);

        DB::beginTransaction();

        // try {

        // Convert the custom formatted date to timestamp
        $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->input('purchase_date'));
        $cattle->purchase_date      = $purchaseDate->toDateTimeString();
        $cattle->purchase_price     = $request->purchase_price;
        $cattle->purchase_weight    = $request->purchase_weight;
        $cattle->save();

        // --------------------------------- cattle record create and update----------------------------------

        $last_updated_weight          = now()->diffInDays($cattle->lastCattleRecord->valid_from_date);
        $lastRecord                   = $cattle->lastCattleRecord;
        $lastRecord->valid_until_date = now();
        $lastRecord->save();

        $cattleRecord                      = new CattleRecord();
        $cattleRecord->cattle_id           = $cattle->id;
        $cattleRecord->purchase_date       = $purchaseDate->toDateTimeString();
        $cattleRecord->purchase_weight     = $request->purchase_weight;
        $cattleRecord->price_for_weight    = $request->price_for_weight;
        $cattleRecord->weight_for_price    = $request->weight_for_price;
        $cattleRecord->growth_weight       = $request->growth_weight;
        $cattleRecord->last_updated_weight = $cattle->lastCattleRecord->last_updated_weight + ($last_updated_weight * $cattle->lastCattleRecord->growth_weight);
        $cattleRecord->valid_from_date     = now();
        $cattleRecord->save();


        DB::commit();
        $toast[] = ['success', 'Cattle weight updated successfully'];
        return back()->withToasts($toast);
        // } catch (\Exception $exp) {
        //     DB::rollBack();
        //     $toast[] = ['error', 'Something went wrong! Cattle creation failed.'];
        //     return back()->withToasts($toast);
        // }
    }

    public function cattleImageDelete($id)
    {
        $cattleImage = CattleImage::findOrFail($id);
        try {
            fileManager()->removeFile(getFilePath('cattle') . '/' . $cattleImage->image);
            $cattleImage->delete();
            $toast[] = ['success', 'Cattle image delete successfully.'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            $toast[] = ['error', 'Something went wrong! Cattle image delete failed.'];
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
                        \Log::warning('File not found: ' . $filePath);
                    }

                    $image->delete();
                }
            }

            $cattle->delete();
            DB::commit();

            $toast[] = ['success', 'Cattle deleted successfully.'];
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Cattle deletion failed: ' . $th->getMessage());
            $toast[] = ['error', 'Something went wrong! Cattle deletion failed.'];
        }

        return back()->withToasts($toast);
    }

    private function generalExpenseDistribute(Request $request, $cattle)
    {
        $cattleCategory = CattleCategory::find($request->cattle_category_id);

        // Common cattle count 
        $cattles = Cattle::whereIn('status', [ManageStatus::CATTLE_BOOKED, ManageStatus::CATTLE_ACTIVE])
            // ->whereHas('cattleCategory', function ($query) {
            //     $query->where('cattle_group', ManageStatus::CATTLE_CATEGORY_COW_GROUP);
            // })
            ->count() + 1;
            // dd($cattles);

        // Load all related general expenses
        $specificGenTotalExpenses = GenTotalExpense::whereIn('expens_type', [
            ManageStatus::GEN_EXPENSE,
            ManageStatus::MEDICINE,
        ])->get();

        // If purchase and cow group
        if (
            $request->type == ManageStatus::PURCHASE_CATTLE &&
            $cattleCategory->cattle_group == ManageStatus::CATTLE_CATEGORY_COW_GROUP
        ) {
            foreach ($specificGenTotalExpenses as $value) {
                $value->per_cattle_expense = $cattles > 0 ? $value->total_amount / $cattles : 0;
                $value->save();
            }
        }

        // If purchase and goat group
        // if (
        //     $request->type == ManageStatus::PURCHASE_CATTLE &&
        //     $cattleCategory->cattle_group == ManageStatus::CATTLE_CATEGORY_GOAT_GROUP
        // ) {
        //     $cattle = Cattle::find($cattle->id); // Refresh cattle object

        //     foreach ($specificGenTotalExpenses as $value) {
        //         if ($value->total_amount >= 500) {
        //             $value->total_amount -= 500;
        //             $value->per_cattle_expense = $cattles > 0 ? $value->total_amount / $cattles : 0;
        //             $value->save();

        //             if ($value->expens_type == ManageStatus::GEN_EXPENSE) {
        //                 $cattle->gen_get_expence = 1;
        //             } elseif ($value->expens_type == ManageStatus::MEDICINE) {
        //                 $cattle->med_get_expense = 1;
        //             }
        //         } else {
        //             // insufficient fund for expense
        //             if ($value->expens_type == ManageStatus::GEN_EXPENSE) {
        //                 $cattle->gen_get_expence = 2;
        //             } elseif ($value->expens_type == ManageStatus::MEDICINE) {
        //                 $cattle->med_get_expense = 2;
        //             }
        //         }
        //     }
        //     $cattle->save(); // Save once outside the loop

        // }
    }

    private function deadGenTotalExpense($deadCattle)
    {

        if ($deadCattle->type == 1 || ($deadCattle->type == 2 && Carbon::parse($deadCattle->purchase_date)->addYear()->lte(now()))) {

            // Common cattle count (only for cow group)
            $cattles = Cattle::whereIn('status', [1, 2])
                ->where('cattle_category_id', ManageStatus::CATTLE_CATEGORY_COW_GROUP)
                ->get()
                ->filter(function ($cattle) {
                    return $cattle->type == 1 || ($cattle->type == 2 && Carbon::parse($cattle->purchase_date)->addYear()->lte(now()));
                })
                ->count();

            // Load all related general expenses
            $specificGenTotalExpenses = GenTotalExpense::whereIn('expens_type', [
                ManageStatus::GEN_EXPENSE,
                ManageStatus::MEDICINE,
            ])->get();

            foreach ($specificGenTotalExpenses as $value) {
                $value->total_amount -= ($cattles - 1) > 0 ? $value->per_cattle_expense : $value->total_amount;
                $value->save();
            }
        }
    }
}
