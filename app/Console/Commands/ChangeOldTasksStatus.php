<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Repositories\Eloquent\TaskRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeOldTasksStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-old-tasks-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command changes the status of tasks that are two days old ';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $now = Carbon::now();

        Task::where('created_at', '<', $now->subDays(2))
            ->where('completed', false)
            ->update(['completed' => true]);

        $this->info('Old tasks have been completed successfully.');

    }
}
