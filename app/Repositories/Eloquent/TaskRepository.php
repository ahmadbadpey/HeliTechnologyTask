<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }


    public function findByUser($user): Collection
    {
        return Task::where('user_id' , $user->id)->get();
    }

    public function changeStatus(int $taskId, bool $isCompleted  , $user): void
    {
        $task = $this->model->find($taskId);

        if ($task === null) {
            throw new ModelNotFoundException('Task not found');
        }
        if ($task->user_id !== $user->id) {
            throw new HttpException(403);
        }

        $task->completed = $isCompleted;
        $task->save();
    }

}
