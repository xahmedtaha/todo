<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskListRequest;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;

class TaskListController extends Controller
{
    public function index()
    {
        return TaskListResource::collection(TaskList::where('user_id', auth()->id())->get());
    }

    public function store(TaskListRequest $request)
    {
        return new TaskListResource(TaskList::create($request->validated()));
    }

    public function show(TaskList $taskList)
    {
        if ($taskList->user_id !== auth()->id()) abort(403);

        return new TaskListResource($taskList);
    }

    public function update(TaskListRequest $request, TaskList $taskList)
    {
        if ($taskList->user_id !== auth()->id()) abort(403);

        $taskList->update($request->validated());
        return new TaskListResource($taskList);
    }

    public function destroy(TaskList $taskList)
    {
        if ($taskList->user_id !== auth()->id()) abort(403);

        $taskList->delete();

        return response()->json();
    }
}
