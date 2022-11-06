<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskControllerStoreRequest;
use App\Http\Requests\TaskControllerUpdateRequest;
use App\Http\Resources\SuccessfulResponse;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::query()
            ->select(['name', 'is_done', 'created_at'])
            ->where('user_id', Auth::user()->id)
            ->get();

        return response()->json(SuccessfulResponse::make($tasks)->resolve());
    }

    /**
     * @param TaskControllerStoreRequest $request
     * @return JsonResponse
     */
    public function store(TaskControllerStoreRequest $request): JsonResponse
    {
        $name = $request->input('name');

        $newTask = new Task();

        $newTask->name = $name;
        $newTask->user_id = Auth::user()->id;
        $newTask->save();

        return response()->json(SuccessfulResponse::make($newTask)->resolve());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::query()
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if (!$task) {
            return response()->json(
                [
                    'error_code' => 404,
                    'message' => 'The task has not been found!'
                ],
                404
            );
        }

        return response()->json(SuccessfulResponse::make($task)->resolve());
    }

    /**
     * @param TaskControllerUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(TaskControllerUpdateRequest $request, int $id): JsonResponse
    {
        $name = $request->input('name');
        $isDone = $request->boolean('is_done');

        $task = Task::query()
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if (!$task) {
            return response()->json(
                [
                    'error_code' => 404,
                    'message' => 'The task has not been found!'
                ],
                404
            );
        }

        $task->name = $name;
        $task->is_done = $isDone;
        $task->save();

        return response()->json(SuccessfulResponse::make($task)->resolve());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = Task::query()
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->delete();

        if (!$result) {
            return response()->json(
                [
                    'error_code' => 404,
                    'message' => 'The task has not been found!'
                ],
                404
            );
        }

        // 201 HTTP code means no response payload
        return response()->json(null, 201);
    }
}
