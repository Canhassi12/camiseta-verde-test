<?php

namespace App\Console;

use App\Console\Commands\NotifyClientCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        NotifyClientCron::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notify:cron')->everyMinute();
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
