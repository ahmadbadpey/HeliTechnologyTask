<?php

namespace Tests\Feature;

use App\Models\Task;
use Tests\TestCase;

class BroadcastingTaskStatusToOtherDevices extends TestCase
{
    public function test_task_status_is_updated_on_other_devices()
    {
        $task = Task::factory()->create();

        $this->putJson("/api/tasks/$task->id", ['completed' => true]);

        $this->assertBroadcastedEvent('task.updated', $task->toArray());
    }

    public function test_multiple_task_statuses_are_updated_on_other_devices()
    {
        // Create multiple tasks
        $tasks = Task::factory()->count(5)->create();

        $this->putJson('/api/tasks/bulk', ['tasks' => $tasks->map(fn ($task) => ['id' => $task->id, 'completed' => true])]);

        foreach ($tasks as $task) {
            $this->assertBroadcastedEvent('task.updated', $task->toArray());
        }
    }

    public function test_other_task_statuses_are_not_updated()
    {
        $tasks = Task::factory()->count(5)->create();

        $task = $tasks->first();
        $this->putJson("/api/tasks/$task->id", ['completed' => true]);

        foreach ($tasks->where('id', '!=', $task->id) as $otherTask) {
            $this->assertNotBroadcastedEvent('task.updated', $otherTask->toArray());
        }
    }


}
