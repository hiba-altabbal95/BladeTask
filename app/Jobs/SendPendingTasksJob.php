<?php

namespace App\Jobs;

use App\Mail\PendingTasksMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPendingTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $pendingTasks = Task::where('user_id', $user->id)
                ->where('status', 'pending')
                ->whereDate('due_date', now()->toDateString())
                ->get();

            if ($pendingTasks->isNotEmpty()) {
                Mail::to($user->email)->send(new PendingTasksMail($pendingTasks));
            }
        }
    }
}
