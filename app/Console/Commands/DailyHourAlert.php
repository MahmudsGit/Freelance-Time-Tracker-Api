<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DailyHourAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-hour-alert';

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
        $users = User::all();
        $today = now()->format('Y-m-d');

        foreach ($users as $user) {
            $hours = $user->clients()
                ->with('projects.timeLogs')
                ->get()
                ->flatMap->projects
                ->flatMap->timeLogs
                ->filter(function ($log) use ($today) {
                    return \Carbon\Carbon::parse($log->start_time)->format('Y-m-d') === $today;
                })->sum('hours');

            if ($hours >= 8) {
                Mail::raw("You've logged $hours hours today.", function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Daily Time Log Notification');
                });
            }
        }
    }
}
