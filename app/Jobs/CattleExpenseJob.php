<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Cattle;
use App\Models\CattleExpense;
use App\Models\CattleRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CattleExpenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Cattle $cattle;

    public $tries = 5;

    public function backoff(): array
    {
        return [10, 20, 35];
    }

    public function __construct(Cattle $cattle)
    {
        $this->cattle = $cattle;
    }

    public function handle(): void
    {
        if (!$this->cattle) return;

        try {
            $existingExpense = CattleExpense::where('cattle_id', $this->cattle->id)->first();
            $yesterdayStart = now()->subDay()->startOfDay();
            $yesterdayEnd = now()->subDay()->endOfDay();

            if ($existingExpense) {
                $this->updateExpense($existingExpense, $yesterdayStart, $yesterdayEnd);
            } elseif ($this->cattle->type == 2 && Carbon::parse($this->cattle->purchase_date)->addYear() < now()) {
                $this->createExpenseForBornCattle($yesterdayStart, $yesterdayEnd);
            } elseif ($this->cattle->type == 1) {
                $this->createExpenseForPurchasedCattle($yesterdayEnd);
            }
        } catch (Exception $e) {
            Log::error('CattleExpenseJob Error: ' . $e->getMessage(), ['cattle_id' => $this->cattle->id]);
        }
    }

    private function updateExpense(CattleExpense $expense, $start, $end): void
    {
        $record = CattleRecord::where('cattle_id', $this->cattle->id)
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->first();

             

        if ($record) {
            $cost = $this->calculatePerDayCost($record);
            $expense->update([
                'last_date' => $end,
                'total_cost' => $expense->total_cost + $cost,
            ]);
        }
    }

    private function createExpenseForBornCattle($start, $end): void
    {
        $record = CattleRecord::where('cattle_id', $this->cattle->id)
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->first();


        if ($record) {
            CattleExpense::create([
                'cattle_id' => $this->cattle->id,
                'last_date' => $end,
                'total_cost' => $this->calculatePerDayCost($record),
            ]);
        }
    }

    private function createExpenseForPurchasedCattle($until): void
    {
        $firstDate = CattleRecord::where('cattle_id', $this->cattle->id)->oldest()->value('created_at');

        if (!$firstDate) return;

        $records = CattleRecord::where('cattle_id', $this->cattle->id)
            ->whereBetween('created_at', [
                Carbon::parse($firstDate)->startOfDay(),
                $until
            ])
            ->get();

        $totalCost = 0;

        foreach ($records as $record) {
            $from = Carbon::parse($record->valid_from_date);
            $to = $record->valid_until_date
                ? Carbon::parse($record->valid_until_date)
                : $until;

            if ($from->toDateString() === $to->toDateString()) {
                $days = 1;
            } else {
                $days = $from->startOfDay()->diffInDays($to->startOfDay()) + 1;
            }

            $perDayCost = $this->calculatePerDayCost($record);
            $totalCost += ($perDayCost * $days);
        }

        if ($totalCost > 0) {
            CattleExpense::create([
                'cattle_id' => $this->cattle->id,
                'last_date' => $until,
                'total_cost' => $totalCost,
            ]);
        }
    }

    private function calculatePerDayCost(CattleRecord $record): float
    {
        $totalWeight = $record->purchase_weight + $record->growth_weight;

        if ($record->price_for_weight == 0) {
            return 0; // avoid division by zero
        }

        return ($totalWeight / $record->price_for_weight) * $record->weight_for_price;
    }
}
