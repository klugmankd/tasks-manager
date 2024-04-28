<?php

namespace App\Services;

use App\DataTransferObjects\TaskDTO;
use App\Http\Resources\Collections\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;

class TaskService
{

    public function __construct(private TaskRepository $repository)
    {}

    public function readAll(User $user): TaskCollection
    {
        return new TaskCollection($this->repository->readAll($user));
    }

    public function create(User $user, TaskDTO $taskDTO): TaskResource
    {
        $task = $this->repository->create($user, $taskDTO);
        return new TaskResource($task);
    }

    public function update(Task $task, TaskDTO $taskDTO): TaskResource
    {
        $task = $this->repository->update($task, $taskDTO);
        return new TaskResource($task);
    }

    public function delete(Task $task): void
    {
        $this->repository->delete($task);
    }
}
