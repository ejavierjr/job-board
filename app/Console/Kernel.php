<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchExternalJobs;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $service = new \App\Services\ExternalJobService();
            $xml = $service->fetchJobs();
            $jobs = $service->parseJobs($xml);
            
            // Store in cache for 1 hour
            \Illuminate\Support\Facades\Cache::put(
                'external-jobs', 
                $jobs,
                3600
            );
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
