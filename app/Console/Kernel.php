<?php

namespace App\Console;

use App\Jobs\SendNewsletterJob;
use App\Models\NewsletterSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $now = now();
            $currentDay = $now->format('l'); // Monday, Tuesday...
            $currentDate = $now->day;
            $currentTime = $now->format('H:i');

            $schedules = NewsletterSchedule::whereTime('send_time', $currentTime)->get();

            foreach ($schedules as $scheduleItem) {
                if (
                    ($scheduleItem->frequency === 'weekly' && $scheduleItem->day_of_week === $currentDay) ||
                    ($scheduleItem->frequency === 'monthly' && $scheduleItem->day_of_month == $currentDate) ||
                    ($scheduleItem->frequency === 'bi-monthly' && in_array($currentDate, [1, 15]))
                ) {
                    dispatch(new SendNewsletterJob($scheduleItem->state_id));
                }
            }
        })->everyMinute();
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
