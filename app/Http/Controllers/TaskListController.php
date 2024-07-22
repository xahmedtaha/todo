<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskListRequest;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;

class TaskListController extends Controller
{
    public function index()
    {
        return TaskListResource::collection(TaskList::all());
    }

    public function store(TaskListRequest $request)
    {
        return new TaskListResource(TaskList::create($request->validated()));
    }

    public function show(TaskList $taskList)
    {
        return new TaskListResource($taskList);
    }

    public function update(TaskListRequest $request, TaskList $taskList)
    {
        $taskList->update($request->validated());

        return new TaskListResource($taskList);
    }

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();

        return response()->json();
    }
}
