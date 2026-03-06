<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Cattle;
use App\Models\AccountHead;
use Illuminate\Http\Request;
use App\Models\AccountSubHead;
use App\Models\GeneralExpense;
use App\Constants\ManageStatus;
use App\Models\GenTotalExpense;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GeneralExpenseController extends Controller
{

    public function index($val)
    {
        
        if ($val == 'food') {
            $expTyp = ManageStatus::FOOD;
        } elseif ($val == 'medicine') {
            $expTyp = ManageStatus::MEDICINE;
        } elseif ($val == 'general') {
            $expTyp = ManageStatus::GEN_EXPENSE;
        } elseif ($val == 'cattle') {
            $expTyp = ManageStatus::CATTLE;
        } else {
            $expTyp = 0;
        }
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $pageTitle = ucfirst($val).' Expense History';
        $genExpenses = GeneralExpense::searchable(['name'])->where('expense_type', $expTyp)->dateFilter()->orderBy('id', 'desc')->latest()->paginate(getPaginate());
        return view('admin.accounts.genexpense_index', compact('pageTitle', 'genExpenses', 'expTyp'));
    }

    function create()
    {
        $pageTitle = 'Expense Create';
        // $accountHeads = AccountHead::orderBy('id')->with('subHeads')->latest()->get();
        // $accSubHeads  = AccountSubHead::orderBy('id')->latest()->get();
        return view('admin.accounts.genexpense_create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $expTyp = '';

        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate(
            [
                // 'acc_head'         => 'required|exists:account_heads,id' ,
                // 'acc_sub_head'     => 'required|exists:account_sub_heads,id',
                'cost_of_amount'   => 'required|numeric|min:1|regex:/^\d+(\.\d{1,2})?$/',
                'date_of_expense'  => 'required|date_format:d/m/Y|before_or_equal:today',
                'expn_purpose'     => 'required|string',
                'description'      => 'nullable|string',
                'expense_type'     => 'required|in:1,2,3,4',
            ],
            [
                // 'acc_head.required'        => 'The account head is not exist.',
                // 'acc_sub_head.required'    => 'The account sub head is not exist.',
                'cost_of_amount.numeric'  => 'The cost of amount must be a number.',
                'expn_purpose.required'   => 'The purpose of expense is required.',
                'expense_type.in'         => 'Please select the expense type.',

            ]
        );

        DB::beginTransaction();

        try {
            $dateOfExpense = Carbon::createFromFormat('d/m/Y', $request->input('date_of_expense'));
            $genExpense = new GeneralExpense();
            // $genExpense->account_head_id      = $request->acc_head;
            // $genExpense->account_sub_head_id  = $request->acc_sub_head;
            $genExpense->expense_type  = $request->expense_type;
            $genExpense->expense_date  = $dateOfExpense->toDateTimeString();
            $genExpense->cost_amount   = $request->cost_of_amount;
            $genExpense->purpose       = $request->expense_purpose;
            $genExpense->note          = $request->description;

            if ($request->expense_type == ManageStatus::FOOD) {
                $expTyp = 'FOOD';
            } elseif ($request->expense_type == ManageStatus::CATTLE) {
                $expTyp = 'CATTLE';
            } elseif ($request->expense_type == ManageStatus::GEN_EXPENSE) {
                $expTyp = 'GENERAL';
            } else {
                $expTyp = 'MEDICINE';
            }

            $genExpense->save();
            $this->generalExpenseDistribute($request);
            DB::commit();
            $toast[] = ['success', $expTyp . ' Expense added successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Expense creation failed.'];
            return back()->withToasts($toast);
        }
    }

// Account Head Update
    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'admin check edit');

        $request->validate([
            'head_code' => ['required', Rule::unique('account_heads', 'acc_head_code')->ignore($id)],
            'name'      => ['required', 'string', Rule::unique('account_heads', 'name')->ignore($id)],
            'status'    => 'required|in:0,1',
        ], [
            'name.required'    => 'The account head name is required.',
            'name.string'      => 'The account head name must be a string.',
            'name.unique'      => 'The account head name has already been taken.',
            'head_code.unique' => 'The account head code has already been taken.',
            'status.required'  => 'The status is required.',
            'status.in'        => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        $accountHead = AccountHead::findOrFail($id);
        $accountHead->acc_head_code = $request->head_code;
        $accountHead->name          = $request->name;
        $accountHead->description   = $request->description;
        $accountHead->status        = $request->status;
        $accountHead->save();
        $toast[] = ['success', 'Cattle Category update successfully'];
        return back()->withToasts($toast);
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

        $this->distributeExpenseToGoats($goatCattles, $specificGenTotalExpense, $cowCattleCount);

        $specificGenTotalExpense->per_cattle_expense = $cowCattleCount > 0
            ? $specificGenTotalExpense->total_amount / $cowCattleCount
            : 0;

        $specificGenTotalExpense->save();
    }

    private function updateOrCreateGeneralExpense(Request $request): GenTotalExpense
    {
        $expense = GenTotalExpense::firstOrNew(['expens_type' => $request->expense_type]);
        $expense->total_amount = ($expense->exists ? $expense->total_amount : 0) + $request->cost_of_amount;
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
}
