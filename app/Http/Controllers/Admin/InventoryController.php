<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Cattle;
use App\Models\GeneralExpense;
use App\Models\GenTotalExpense;
use App\Models\InventoryIssue;
use App\Models\InventoryStore;
use App\Models\InvStkQuantity;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Warehouse;
use Carbon\Carbon;
use HTMLPurifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;

class InventoryController extends Controller
{
    // Inventory Stock Start Here
    function stockIndex($val) //Warehouse wise stokc list show
    {

        // dd('hello');
        $this->authorizeForAdmin('has-permission', 'inventory list');
        $warehouse = Warehouse::orderBy('id')->where('name', $val)->latest()->first();
        $pageTitle = $warehouse->name . ' Stock';

        // Subquery 1: Latest inventory_store
        $latestStoreSub = DB::table('inventory_stores as invStore')
            ->select('invStore.id')
            ->whereColumn('invStore.item_id', 'inv_stk_quantities.item_id')
            ->whereColumn('invStore.warehouse_id', 'inv_stk_quantities.warehouse_id')
            ->orderByDesc('invStore.id')
            ->limit(1);

        // Subquery 2: Latest inventory_issue
        $latestIssueSub = DB::table('inventory_issues as invIssue')
            ->select('invIssue.id')
            ->whereColumn('invIssue.item_id', 'inv_stk_quantities.item_id')
            ->whereColumn('invIssue.warehouse_id', 'inv_stk_quantities.warehouse_id')
            ->orderByDesc('invIssue.id')
            ->limit(1);

        // Main query
        $invStocks = InvStkQuantity::query()
            ->select(
                'inv_stk_quantities.*',
                'inventory_stores.purchase_date',
                'inventory_stores.quantity_in',
                'inventory_stores.supplier_id',
                'inventory_issues.issue_date as last_issue_date',
                'inventory_issues.quantity as quantity_out'
            )
            ->where('inv_stk_quantities.quantity', '>=', 0)
            ->whereHas('warehouse', function ($q) use ($val) {
                $q->where('name', $val);
            })

            // Join latest inventory_store
            ->leftJoin('inventory_stores', function ($join) use ($latestStoreSub) {
                $join->on('inventory_stores.id', '=', DB::raw("({$latestStoreSub->toSql()})"))
                    ->mergeBindings($latestStoreSub);
            })

            // Join latest inventory_issue
            ->leftJoin('inventory_issues', function ($join) use ($latestIssueSub) {
                $join->on('inventory_issues.id', '=', DB::raw("({$latestIssueSub->toSql()})"))
                    ->mergeBindings($latestIssueSub);
            })

            ->with(['item', 'warehouse']) // eager load relationships
            ->paginate(getPaginate());
        // dd($invStocks);
        return view('admin.inventory_manage.wh_stk_index', compact('pageTitle', 'invStocks', 'warehouse'));
    }



    function stockHistory($val) //All stock list 
    {
        $this->authorizeForAdmin('has-permission', 'stock list');
        $warehouse = Warehouse::orderBy('id')->where('name', $val)->latest()->first();
        $pageTitle = $warehouse->name . ' Stock History';

        $invStkHistory = InventoryStore::searchable(['name'])
            ->with(['item', 'supplier', 'warehouse'])

            ->whereHas('warehouse', function ($query) use ($val) {
                $query->where('name', $val);
            })
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());

        // dd($invStkHistory);
        return view('admin.inventory_manage.inv_stk_index', compact('pageTitle', 'invStkHistory', 'warehouse'));
    }


    function create($val)
    {

        // dd('create');
        if ($val == 'Food Store') {
            $expTyp = ManageStatus::FOOD;
        } elseif ($val == 'Medicine Store') {
            $expTyp = ManageStatus::MEDICINE;
        } elseif ($val == 'General Store') {
            $expTyp = ManageStatus::GEN_EXPENSE;
        } else {
            $expTyp = 0;
        }

        $pageTitle = 'Stock Item';
        $categoryId = Category::where('id', $expTyp)->latest()->first();
        $items = Item::orderBy('id')->where('category_id', $categoryId->id)->latest()->get();
        $categories = Category::orderBy('id')->latest()->get();
        $suppliers = Supplier::orderBy('id')->where('supplier_type', $expTyp)->where('status', 1)->latest()->get();
        $warehouse = Warehouse::where('name', $val)->latest()->first();
        return view('admin.inventory_manage.inv_stk_create', compact('pageTitle', 'items', 'suppliers', 'categories', 'warehouse', 'expTyp'));
    }

    function stockQntEdit($val, $id)
    {
        // dd($val, $id);
        if ($val == 'Food Store') {
            $expTyp = ManageStatus::FOOD;
        } elseif ($val == 'Medicine Store') {
            $expTyp = ManageStatus::MEDICINE;
        } elseif ($val == 'General Store') {
            $expTyp = ManageStatus::GEN_EXPENSE;
        } else {
            $expTyp = 0;
        }
        $pageTitle = 'Stock Item';
        $storeItem = Item::where('id', $id)->latest()->first();
        $suppliers = Supplier::orderBy('id')->where('supplier_type', 1)->where('status', 1)->latest()->get();
        $categories = Category::orderBy('id')->latest()->get();
        $warehouse = Warehouse::where('name', $val)->latest()->first();
        return view('admin.inventory_manage.inv_stk_create', compact('pageTitle', 'storeItem', 'suppliers', 'categories', 'warehouse', 'expTyp'));
    }

    function store(Request $request)
    {


        $this->authorizeForAdmin('has-permission', 'stock create');
        $pageTitle = 'Item Store';
        $expTyp = '';
        $totalAmount = 0.00;
        $request->validate(
            [
                'supplier_id'     => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if ($value !== 'new_supplier' && !\App\Models\Supplier::where('id', $value)->exists()) {
                            $fail('The selected supplier is invalid.');
                        }
                    },
                ],

                'category_id'     => ['required_if:supplier_id,new_supplier', 'numeric', 'exists:categories,id',],
                'sup_name'        => 'required_if:supplier_id,new_supplier',
                'contact_number'  => 'required_if:supplier_id,new_supplier',
                'string',
                'sup_address'     => 'nullable',
                'item_id'         => 'required|exists:items,id',
                'warehouse_id'    => 'required|exists:warehouses,id',
                'stock_in'        => 'required|numeric|min:0.1',
                'uom'             => 'required|string',
                'rate_per_unit'   => 'required|numeric',
                'purchase_date'   => 'required|date_format:d/m/Y|before_or_equal:today', // this is also use for date of expens
                'note'            => 'nullable|string', // this is description for expens
                'remark'          => 'required|string', // this is purpose for expense
                'reference'       => 'nullable|string',
                'expense_type'    => 'required|in:1,2,3,4',
            ],
            [
                'code.unique'               => 'The item code has already been taken.',
                'purchase_date.date_format' => 'Purchase date must be in format DD/MM/YYYY.',
                'remark.required'           => 'The is required.',
                'expense_type.in'           => 'Please select the expense type.',
            ]
        );
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Convert the custom formatted date to timestamp
            $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->input('purchase_date'));

            $purifier  = new HTMLPurifier();
            if ($request->supplier_id === 'new_supplier') {
                $supplier = new Supplier();
                $supplier->category_id    = $request->category_id;
                $supplier->first_name     = $request->sup_name;
                $supplier->contact_number = $request->contact_number;
                $supplier->supplier_type  = $request->expense_type;
                $supplier->address        = $purifier->purify($request->sup_address);
                $supplier->save();
            }

            $inventoryStore = new InventoryStore();
            $inventoryStore->item_id             = $request->item_id;
            $inventoryStore->supplier_id         = isset($supplier) ? $supplier->id : $request->supplier_id;
            $inventoryStore->warehouse_id        = $request->warehouse_id;
            $inventoryStore->purchase_date       = $purchaseDate->toDateTimeString();
            $inventoryStore->quantity_in         = $request->stock_in;
            $inventoryStore->unit_of_measurement = $request->uom;
            $inventoryStore->rate_per_unit       = $request->rate_per_unit;
            $inventoryStore->total_amount        = $request->stock_in * $request->rate_per_unit;
            $totalAmount                         = $inventoryStore->total_amount;
            $inventoryStore->remark              = $request->remark;
            $inventoryStore->reference           = $request->reference;
            $inventoryStore->save();
            $existingInventoryStockQuentity = InvStkQuantity::where('item_id', $request->item_id)->where('warehouse_id', $request->warehouse_id)->first();

            if ($existingInventoryStockQuentity) {
                $existingInventoryStockQuentity->quantity += $request->stock_in;
                $existingInventoryStockQuentity->save();
            } else {
                $inventoryStoreQuentity = new InvStkQuantity();
                $inventoryStoreQuentity->item_id             = $request->item_id;
                $inventoryStoreQuentity->warehouse_id        = $request->warehouse_id;
                $inventoryStoreQuentity->quantity            = $request->stock_in;
                $inventoryStoreQuentity->unit_of_measurement = $request->uom;
                $inventoryStoreQuentity->save();
            }


            $dateOfExpense = Carbon::createFromFormat('d/m/Y', $request->input('purchase_date'));
            $genExpense = new GeneralExpense();
            $genExpense->inventory_store_id = $inventoryStore->id;
            $genExpense->expense_type  = $request->expense_type;
            $genExpense->expense_date  = $dateOfExpense->toDateTimeString();

            $genExpense->cost_amount   = $totalAmount;
            $genExpense->purpose       = $request->remark;
            $genExpense->note          = $request->note;
            $genExpense->save();

            $this->generalExpenseDistribute($request);

            DB::commit();
            $toast[] = ['success', 'Inventory Store successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Inventory Store failed.'];
            return back()->withToasts($toast);
        }

        DB::beginTransaction();
    }

    // Inventory Issue Start Here
    function issueIndex()
    {
        $pageTitle = 'Inventory Issue History';
        $invIssues = InventoryIssue::searchable(['name', 'code'])->with('item', 'warehouse')->dateFilter()
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(getPaginate());
        // dd($invStocks);
        return view('admin.inventory_manage.inv_issue_index', compact('pageTitle', 'invIssues'));
    }

    function issueCreate($val, $id)
    {

        // dd($id);
        $pageTitle = 'Create Issue';
        $item = Item::where('id', $id)->latest()->first();
        $warehouse = Warehouse::where('name', $val)->latest()->first();
        // dd($warehouse);
        return view('admin.inventory_manage.inv_issue_create', compact('pageTitle', 'item', 'warehouse'));
    }

    function issueStore(Request $request)
    {

        // dd('hello');
        $pageTitle = 'Issue Created';
        $request->validate([
            'item_id'      => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'issue_qnt'    => 'required|numeric|min:0.01',
            'note'         => 'nullable|string',
            'remark'       => 'nullable|string',
            'issue_ref'    => 'nullable|string',
            'issue_date'   => [
                'required',
                'date_format:d/m/Y',
                function ($attribute, $value, $fail) {
                    $inputDate = \DateTime::createFromFormat('d/m/Y', $value);
                    $today = new \DateTime();

                    if (!$inputDate) {
                        $fail('The issue date is invalid.');
                        return;
                    }

                    if ($inputDate > $today) {
                        $fail('The issue date cannot be in the future.');
                    }
                }
            ]
        ], [
            'code.unique'            => 'The item code has already been taken.',
            'issue_date.date_format' => 'Purchase date must be in format DD/MM/YYYY.'
        ]);
        DB::beginTransaction();
        try {

            // Convert the custom formatted date to timestamp
            $issueDate = Carbon::createFromFormat('d/m/Y', $request->input('issue_date'));

            $inventoryIssue = new InventoryIssue();
            $inventoryIssue->item_id          = $request->item_id;
            $inventoryIssue->warehouse_id     = $request->warehouse_id;
            $inventoryIssue->issue_date       = $issueDate->toDateTimeString();
            $inventoryIssue->quantity         = $request->issue_qnt;
            $inventoryIssue->remark           = $request->remark;
            $inventoryIssue->issue_department = $request->issue_ref;
            $inventoryIssue->save();
            $existingInventoryStockQuentity = InvStkQuantity::where('item_id', $request->item_id)->where('warehouse_id', $request->warehouse_id)->first();
            // dd($request->all(), $existingInventoryStockQuentity);


            if ($existingInventoryStockQuentity->quantity >= $request->issue_qnt) {
                $existingInventoryStockQuentity->quantity -= $request->issue_qnt;
                $existingInventoryStockQuentity->save();
            } else {
                $toast[] = ['error', 'Issue Quantity Exceed Stock Quantity.'];
                return back()->withToasts($toast);
            }

            DB::commit();
            $toast[] = ['success', 'Inventory issue create successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Inventory Store failed.'];
            return back()->withToasts($toast);
        }
    }














    // Distribute the Gen exp to Cattles
    private function generalExpenseDistribute(Request $request)
    {
        $specificGenTotalExpense = $this->updateOrCreateGeneralExpense($request);

        if (!in_array($request->expense_type, [ManageStatus::GEN_EXPENSE, ManageStatus::MEDICINE])) {
            return;
        }

        $cowCattleCount = $this->getEligibleCattleCount(ManageStatus::CATTLE_CATEGORY_COW_GROUP);
        $goatCattles = $this->getEligibleCattles(ManageStatus::CATTLE_CATEGORY_GOAT_GROUP);

        // $this->distributeExpenseToGoats($goatCattles, $specificGenTotalExpense, $cowCattleCount);

        $specificGenTotalExpense->per_cattle_expense = $cowCattleCount > 0
            ? $specificGenTotalExpense->total_amount / $cowCattleCount
            : 0;

        $specificGenTotalExpense->save();
    }

    private function updateOrCreateGeneralExpense(Request $request): GenTotalExpense
    {
        $expense = GenTotalExpense::firstOrNew(['expens_type' => $request->expense_type]);
        $expense->total_amount = ($expense->exists ? $expense->total_amount : 0) + ($request->stock_in * $request->rate_per_unit);
        $expense->save();

        return $expense;
    }

    private function getEligibleCattleCount(int $categoryId): int
    {
        return $this->getEligibleCattles($categoryId)->count();
    }

    private function getEligibleCattles(int $categoryId)
    {
        return Cattle::whereIn('status', [ManageStatus::CATTLE_BOOKED, ManageStatus::CATTLE_ACTIVE])
            // ->whereHas('cattleCategory', function ($query) { $query->where('cattle_group', ManageStatus::CATTLE_CATEGORY_COW_GROUP); })
            ->get();
    }

    private function distributeExpenseToGoats($goatCattles, GenTotalExpense $expense, int $cowCount): void
    {
        foreach ($goatCattles as $goat) {
            $updated = false;

            if (
                $expense->expens_type == ManageStatus::GEN_EXPENSE &&
                $goat->gen_get_expence == 2 &&
                $expense->total_amount >= 500
            ) {
                $expense->total_amount -= 500;
                $goat->gen_get_expence = 1;
                $updated = true;
            }

            if (
                $expense->expens_type == ManageStatus::MEDICINE &&
                $goat->med_get_expense == 2 &&
                $expense->total_amount >= 500
            ) {
                $expense->total_amount -= 500;
                $goat->med_get_expense = 1;
                $updated = true;
            }

            if ($updated) {
                $goat->save();
            }
        }
    }



    // =============stock edit function ==================
    function stockEdit($val, $id)
    {
        // dd($id);
        if ($val == 'Food Store') {
            $expTyp = ManageStatus::FOOD;
        } elseif ($val == 'Medicine Store') {
            $expTyp = ManageStatus::MEDICINE;
        } elseif ($val == 'General Store') {
            $expTyp = ManageStatus::GEN_EXPENSE;
        } else {
            $expTyp = 0;
        }
        $pageTitle = 'Edit Item Stock';
        $suppliers = Supplier::orderBy('id')->where('status', 1)->latest()->get();
        $warehouse = Warehouse::get();
        $itemDetails = InventoryStore::where('id', $id)->with(['item', 'supplier', 'warehouse'])->latest()->first();
        // dd($itemDetails->supplier->id);
        $storeItem = Item::get();
        return view('admin.inventory_manage.inv_stk_history_update', compact('pageTitle', 'itemDetails', 'suppliers', 'storeItem', 'warehouse', 'expTyp'));
    }



    // =============================stock history update function ==================
    function stockHistoryEdit($val, $id)
    {
        // dd($id);
        $this->authorizeForAdmin('has-permission', 'stock list');
        $warehouse = Warehouse::orderBy('id')->where('name', $val)->latest()->first();
        $pageTitle = $warehouse->name . ' Stock History';

        $invStkHistory = InventoryStore::searchable(['name'])
            ->where('item_id', $id)
            ->with(['item', 'supplier', 'warehouse'])

            // ->whereHas('warehouse', function ($query) use ($val) { $query->where('name', $val); })
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());
        return view('admin.inventory_manage.inv_stk_history', compact('pageTitle', 'invStkHistory', 'warehouse'));
    }




    // ======================================== STOCK HISTORY UPDATE POST METHOD ==========================
    function stockHistoryUpdate(Request $request, $id)
    {
        // dd($request->all());
        $this->authorizeForAdmin('has-permission', 'stock list');
        $inventoryStore = InventoryStore::where('id', $id)->latest()->first();
        // dd($inventoryStore);
        if (!$inventoryStore) {
            return back()->withToasts([['error', 'Inventory Store not found']]);
        }




        $request->validate([
            'supplier_id'     => 'required|exists:suppliers,id',




            'item_id'         => 'required|exists:items,id',
            'warehouse_id'    => 'required|exists:warehouses,id',

            'stock_in'        => 'required|numeric|min:0.01',
            'uom'             => 'required|string|max:50',
            'rate_per_unit'   => 'required|numeric|min:0',

            'purchase_date'   => 'required|date_format:d/m/Y|before_or_equal:today',
            'note'            => 'nullable|string|max:1000',
            'remark'          => 'nullable|string|max:500',
            'reference'       => 'nullable|string|max:255',
        ]);
        // dd($request->all());
        DB::beginTransaction();
        try {

            $oldQuantity = $inventoryStore->quantity_in;

            $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->purchase_date);


            // 🔹 Update Inventory Store
            $inventoryStore->item_id             = $request->item_id;
            $inventoryStore->supplier_id         = $request->supplier_id;
            $inventoryStore->warehouse_id        = $request->warehouse_id;
            $inventoryStore->purchase_date = $purchaseDate->toDateTimeString();
            $inventoryStore->quantity_in         = $request->stock_in;
            $inventoryStore->unit_of_measurement = $request->uom;
            $inventoryStore->rate_per_unit       = $request->rate_per_unit;
            $inventoryStore->total_amount        = $request->stock_in * $request->rate_per_unit;
            $inventoryStore->remark              = $request->remark ?? $inventoryStore->remark;
            $inventoryStore->reference           = $request->reference ?? $inventoryStore->reference;
            $inventoryStore->save();

            $totalAmount = $inventoryStore->total_amount;
            // dd($totalAmount);
            // 🔹 Stock Quantity Adjustment
            $stock = InvStkQuantity::where('item_id', $request->item_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->first();
            // dd($stock);
            if ($stock) {
                // remove old stock & add new stock
                $stock->quantity = ($stock->quantity - $oldQuantity) + $request->stock_in;
                $stock->save();
            }
            // dd($stock);
            // 🔹 Update General Expense
            $expense = GeneralExpense::where('inventory_store_id', $inventoryStore->id)->first();
            // dd($expense);
            if ($expense) {
                $expense->expense_type = $request->expense_type;
                $expense->expense_date = $purchaseDate->toDateTimeString();
                $expense->cost_amount  = $totalAmount;
                $expense->purpose      = $request->remark ?? $expense->purpose;
                $expense->note         = $request->note ?? $expense->note;
                $expense->save();
                // dd($expense);
            }

            // 🔹 Expense distribute (if needed)
            $this->generalExpenseDistribute($request);
            // dd($request->all());
            DB::commit();
            return back()->withToasts([['success', 'Inventory updated successfully']]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withToasts([['error', 'Something went wrong! Inventory update failed']]);
        }
    }
}
