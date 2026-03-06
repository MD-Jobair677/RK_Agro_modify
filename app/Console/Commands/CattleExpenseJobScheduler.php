<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Cattle;
use App\Models\CattleRecord;
use App\Models\CattleExpense;
use App\Jobs\CattleExpenseJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CattleExpenseJobScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cattle-expense-job-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch cattle expense job every 15 days based on the last entry date.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting to dispatch user jobs...");

        try {
            // get all cattles
            Cattle::where('status', 1)->chunk(100, function ($cattles) {
                foreach ($cattles as $cattle) {
                    try {
                        CattleExpenseJob::dispatch($cattle);
                    } catch (\Exception $e) {
                        Log::error("Dispatch failed for user ID {$cattle->id}: " . $e->getMessage());
                    }
                }
            });
        } catch (\Exception $e) {
            Log::critical("Chunking failed: " . $e->getMessage());
        }

        $this->info("Job dispatching complete.");
    }
}
