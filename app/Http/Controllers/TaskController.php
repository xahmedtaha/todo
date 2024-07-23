<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tasks = $user->tasks();

        if ($request->has('taskListId')) $tasks->where('task_list_id', $request->taskListId);
        if ($request->has('sortBy') && in_array($request->sortBy, ['id', 'title', 'due_date'])) $tasks->orderBy($request->sortBy);

        return TaskResource::collection($tasks->paginate(5));
    }

    public function getDeletedTasks()
    {
        $user = auth()->user();
        $tasks = $user->tasks()->onlyTrashed();
        return new TaskResource($tasks->paginate(5));
    }

    public function store(TaskRequest $request)
    {
        return new TaskResource(Task::create($request->validated()));
    }

    public function show(Task $task)
    {
        if($task->user_id !== auth()->id()) abort(403);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        if($task->user_id !== auth()->id()) abort(403);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        if($task->user_id !== auth()->id()) abort(403);

        $task->delete();

        return response()->json();
    }

    public function restore(Task $task)
    {
        if($task->user_id !== auth()->id()) abort(403);

        if ($task->trashed()) $task->restore();

        return new TaskResource($task);
    }
}
