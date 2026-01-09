<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeLeave;
use App\Models\LeaveIncrement;
use Carbon\Carbon;

class IncrementLeaveBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:increment-leave-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment leave balances for employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');
        $monthToStore = $currentMonth . '-' . $currentYear;

        // CL increment (yearly)
        if ($currentMonth == 'January') {
            $clIncrement = LeaveIncrement::where('month', 'January-' . $currentYear)->where('type', 'CL')->exists();
            if (!$clIncrement) {
                LeaveIncrement::create([
                    'month' => 'January-' . $currentYear,
                    'type' => 'CL',
                ]);
                EmployeeLeave::query()->update(['cl' => 30, 'cl_in_hand' => 30]);
                $this->info('Casual Leave incremented for ' . $currentYear);
            }
        }

        // EL increment (yearly)
        if ($currentMonth == 'January') {
            $elIncrement = LeaveIncrement::where('month', 'January-' . $currentYear)->where('type', 'EL')->exists();
            if (!$elIncrement) {
                LeaveIncrement::create([
                    'month' => 'January-' . $currentYear,
                    'type' => 'EL',
                ]);
                EmployeeLeave::query()->increment('el', 30);
                EmployeeLeave::query()->increment('el_in_hand', 30);
                $this->info('Earned Leave incremented for ' . $currentYear);
            }
        }

        // ML increment (half-yearly)
        if ($currentMonth == 'January' || $currentMonth == 'July') {
            $mlIncrement = LeaveIncrement::where('month', $monthToStore)->where('type', 'ML')->exists();
            if (!$mlIncrement) {
                LeaveIncrement::create([
                    'month' => $monthToStore,
                    'type' => 'ML',
                ]);
                EmployeeLeave::query()->increment('ml', 15);
                EmployeeLeave::query()->increment('ml_in_hand', 15);
                $this->info('Medical Leave incremented for ' . $monthToStore);
            }
        }
    }
}
