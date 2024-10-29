<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest as RequestsStoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskRequest\AssignTaskUser;
use App\Http\Requests\TaskRequest\StoreTaskRequest;
use App\Http\Requests\TaskRequest\TaskAttachementRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskStatusRequest;
use App\Http\Requests\UpdateTaskRequest as RequestsUpdateTaskRequest;
use App\Models\Task;
use App\Services\ApiResponseService;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return view
     */
    public function index()  
    {  $tasks = $this->taskService->getTasksForToday();
        return view('tasks.index', compact('tasks'));
         
      //  return ApiResponseService::paginated($task, 'tasks retrieved successfully');
    }

    /**
     * Store a new Task.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(RequestsStoreTaskRequest $request)
    {
        // Validate the request data
        $data = $request->validated();

        // Create a new Task with the validated data
        $task = $this->taskService->createTask($data);

        return redirect()->route('tasks.index');
        // Return a success response with the created Task data
      //  return ApiResponseService::success($task, 'Task created successfully', 201);
    }

    /**
     * Show details of a specific Task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        
        // Retrieve the details of the book by its ID
        $task = $this->taskService->getTask($id);
    

        // Return a success response with the Task details
        return ApiResponseService::success($task, 'Task details retrieved successfully');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

     /**
     * Update the specified resource in storage.
     * @param UpdateuserRequest $request
     * @param int $id
     * @return JsonResponse
     * 
     */
    public function update(RequestsUpdateTaskRequest $request, string $id)
    {
        
        // Validate the request data
        $data = $request->validated();

        // Update the user with the validated data
        $task = $this->taskService->updateTask($data, $id);

        return redirect()->route('tasks.index');
        // Return a success response with the updated user data
      //  return ApiResponseService::success($task, 'task updated successfully');
    }

    /**
     * Delete a specific Task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
    
        // Delete the Task by its ID
        $this->taskService->deleteTask($id);
        return redirect()->route('tasks.index');
        // Return a success response indicating the Task was deleted
      //  return ApiResponseService::success(null, 'Task deleted successfully');
    }

    public function markAsCompleted(Task $task)
    {
        $this->taskService->markAsCompleted($task);
        return redirect()->route('tasks.index');
    }
    

}
