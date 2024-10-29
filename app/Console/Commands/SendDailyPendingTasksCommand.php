<?php

namespace App\Console\Commands;

use App\Jobs\SendPendingTasksJob;
use Illuminate\Console\Command;

class SendDailyPendingTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:pending-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to send daily email for users with pending tasks';

    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
       
        SendPendingTasksJob::dispatch();

        $this->info('Dispatched job to send daily pending tasks emails successfully!');
    }
}
