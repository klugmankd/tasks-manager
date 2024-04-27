<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\DeleteRequest;
use App\Http\Requests\Task\GetAllRequest;
use App\Http\Requests\Task\GetRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Collections\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     title="Tasks API",
 *     version="0.1"
 * )
 */
class TaskController extends Controller
{

    public function __construct(private TaskService $service)
    {}

    public function index(GetAllRequest $request): TaskCollection
    {
        return $this->service->readAll($request->user());
    }

    public function show(GetRequest $request, Task $task): TaskResource
    {
        return new TaskResource($task->load(TaskService::RELATIONS));
    }

    public function store(StoreRequest $request): TaskResource
    {
        return $this->service->create($request->user(), $request->all());
    }

    public function update(UpdateRequest $request, Task $task): TaskResource
    {
        return $this->service->update($task, $request->all());
    }

    public function destroy(DeleteRequest $request, Task $task): JsonResponse
    {
        $this->service->delete($task);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
