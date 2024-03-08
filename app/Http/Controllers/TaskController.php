<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{

    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $tasks = $this->taskRepository->findByUser($user);
        return response()->json([ 'tasks' => $tasks ] , 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): Model
    {

        $data = $request->all();

        if (Auth::user()) {
            $data['user_id'] = Auth::user()->id;
        }

        return $this->taskRepository->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function updateStatus(Request $request): JsonResponse
    {
        $isCompleted = $request->get('completed');
        $taskId = $request->get('task_id');

        try {
            $this->taskRepository->changeStatus($taskId , $isCompleted , auth()->user());

            return response()->json([
                    'success' => true
                ]
            );
        } catch (ModelNotFoundException $exception) {
            abort(404);
        } catch (HttpException $exception) {
            abort(403);
        }

    }

}
