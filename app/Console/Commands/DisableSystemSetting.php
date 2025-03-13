<?php

namespace App\Console\Commands;

use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DisableSystemSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:disable-system-setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $activeSystemSetting = SystemSetting::query()->where("status", true)->first();

        if ($activeSystemSetting) {
            $currentDate = now();

            $finalClosureDate = Carbon::parse($activeSystemSetting->final_closure_date);


            if ($finalClosureDate->lessThan($currentDate)) {
                $activeSystemSetting->update([
                    "status" => false
                ]);
            }
        }
    }
}
