<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $req)
    {
        return TaskResource::collection(
            Task::where('user_id', $req->user()->id)->latest('updated_at')->paginate(20)
        );
    }
    public function store(TaskRequest $req)
    {
        $t = Task::create(array_merge($req->validated(), ['user_id' => $req->user()->id]));
        return new TaskResource($t);
    }
    public function show(Request $req, Task $task)
    {
        abort_if($task->user_id !== $req->user()->id, 403);
        return new TaskResource($task);
    }
    public function update(TaskRequest $req, Task $task)
    {
        abort_if($task->user_id !== $req->user()->id, 403);
        $task->update($req->validated());
        return new TaskResource($task);
    }
    public function destroy(Request $req, Task $task)
    {
        abort_if($task->user_id !== $req->user()->id, 403);
        $task->delete();
        return response()->noContent();
    }
}
