<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompleteOldTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_old_tasks_are_automatically_completed()
    {
        $task = Task::factory()->create([
            'created_at' => Carbon::now()->subDays(3),
        ]);

        $this->assertFalse($task->completed);

        $this->artisan('complete:old-tasks');

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_only_pending_tasks_are_completed()
    {
        $task = Task::factory()->create([
            'created_at' => Carbon::now()->subDays(3),
            'completed' => true,
        ]);

        $pendingTask = Task::factory()->create([
            'created_at' => Carbon::now()->subDays(3),
            'completed' => false,
        ]);

        $this->artisan('complete:old-tasks');

        $this->assertTrue($task->fresh()->completed);

        $this->assertTrue($pendingTask->fresh()->completed);
    }
}
