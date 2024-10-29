<?php

namespace App\Services;


use App\Models\Task;
use App\Models\TaskDependency;
use Exception;
use Faker\Core\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Testing\File as TestingFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Storage as ProvidersStorage;
use Psy\Readline\Hoa\FileException;
use Symfony\Component\Mime\Part\File as PartFile;

class TaskService{



     /**
     * Create a new task.
     *
     * @param array $task
     * @return \App\Models\Task
     */
    public function createTask(array $data)
    {
               
    try {
        // Cache key for tasks
        $cacheKey = 'tasks_for_today';

        // Create a new Task record with the provided data
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'],
            'status' => $data['status'],
        ]);

        // Forget the old cache for today's tasks as a new task is added
        Cache::forget($cacheKey);

        // Optionally, re-cache the tasks for today
        Cache::remember($cacheKey, 60, function () {
            return Task::whereDate('due_date', now()->toDateString())->get();
        });

        return $task;
          
        } catch (Exception $e) {
          Log::error('Error creating Task: ' . $e->getMessage());
          throw new Exception(ApiResponseService::error('Error Creating Task'));
         
        }
    }

     /**
     * Get the details of a specific Task by its ID.
     *
     * @param int $id
     * @return \App\Models\Task
     */
    public function getTask(int $id)
    {
        try {
            // Find the Task by ID or fail with a 404 error if not found
            return Task::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Task not found: ' . $e->getMessage());
            throw new Exception('Task not found.');
        } catch (Exception $e) {
            Log::error('Error retrieving Task: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error retrieving Task'));
        }
    }

     /**
     * Update the details of a specific task.
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Task
     */
    public function updateTask(array $data, int $id)
    {
        try {
            // Cache key for the specific task
        $cacheKey = 'task_' . $id;

        // Find the task by ID or fail with a 404 error if not found
        $task = Task::findOrFail($id);

        // Update the task with the provided data, filtering out null values
        $task->update(array_filter([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'due_date' => $data['due_date'] ?? $task->due_date,
            'status' => $data['status'] ?? $task->status,
            'user_id' => $data['user_id'] ?? $task->user_id,
        ]));

        // Forget the cache key as the task has been updated
        Cache::forget($cacheKey);

        // Optionally, re-cache the updated task
        Cache::put($cacheKey, $task, 60);

        // Return the updated task
        return $task;
        } catch (ModelNotFoundException $e) {
            Log::error('task not found: ' . $e->getMessage());
            throw new Exception('task not found.');
        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error updating task:'));
        }
    }
    /**
     * Delete a specific Task by its ID.
     *
     * @param int $id
     * @return void
     */
    public function deleteTask(int $id)
    {
        try {
            // Cache key for the specific task
            $cacheKey = 'task_' . $id;
    
            // Find the Task by ID or fail with a 404 error if not found
            $task = Task::findOrFail($id);
    
            // Delete the Task
            $task->delete();
    
            // Forget the cache key as the task has been deleted
            Cache::forget($cacheKey);
    
            // Forget the cache for today's tasks to ensure accuracy
            Cache::forget('tasks_for_today');
        } catch (ModelNotFoundException $e) {
            Log::error('Task not found: ' . $e->getMessage());
            throw new Exception('Task not found.');
        } catch (Exception $e) {
            Log::error('Error deleting Task ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error deleting Task'));
        }
    }


        public function getTasksForToday(){
           try{

            // Check if tasks are cached
        $tasks = Cache::remember('tasks_for_today', 10, function () {
            return Task::whereDate('due_date', now()->toDateString())->get();
        });

        return $tasks;
          
           }
         catch (Exception $e) {
            Log::error('Error retrieving task for today ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error retrieving task for today'));
           
        }
    }

   public function markAsCompleted(Task $task)
{
try{
    // Cache key for the specific task
    $cacheKey = 'task_' . $task->id;

    // Update the task status to 'completed'
    $result = $task->update(['status' => 'completed']);

    // Invalidate the cache for the updated task
    Cache::forget($cacheKey);

    // Optionally, re-cache the updated task
    Cache::put($cacheKey, $task, 60);

    // Invalidate the cache for today's tasks to ensure accuracy
    Cache::forget('tasks_for_today');

    return $result;
  //  return $task->update(['status' => 'completed']);
} catch (Exception $e) {
    Log::error('Error retrieving completed task ' . $e->getMessage());
    throw new Exception(ApiResponseService::error('Error retrieving completed task'));
   
}
}

    


   
  


  

}