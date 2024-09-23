<?php

namespace App\Console;


use App\Http\Controllers\Scheduled\ClosedSupportTickets;
use App\Http\Controllers\Scheduled\DeleteOldSales;
use App\Http\Controllers\Scheduled\DeleteOldTokens;
use App\Http\Controllers\Scheduled\ForgotRemove;
use App\Http\Controllers\Scheduled\RatingSchedule;
use App\Http\Controllers\Scheduled\RouteComplete;
use App\Http\Controllers\Scheduled\RouteReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     * @throws \Exception
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new DeleteOldSales())->hourly();
        $schedule->call(new RouteReminder())->everyMinute();
        $schedule->call(new ClosedSupportTickets())->everyMinute();
        $schedule->call(new ForgotRemove())->everyMinute();
        $schedule->call(new RatingSchedule())->everyMinute();
        $schedule->call(new RouteComplete())->everyFiveMinutes();
        $schedule->call(new DeleteOldTokens())->everyFiveMinutes();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
