<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Http\Resources\Collections\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;

class TaskService
{

    public const array RELATIONS = ['assignee', 'author'];

    public function readAll(User $user): TaskCollection
    {
        $tasks = Task::with(self::RELATIONS)
            ->when($user->role->name === RoleEnum::ASSIGNEE->value, function ($query) use ($user) {
                return $query->where('assignee_id', $user->id);
            })
            ->get();
        return new TaskCollection($tasks);
    }

    public function create(User $user, array $requestData): TaskResource
    {
        $task = Task::create([
            'author_id' => $user->id,
            ...$requestData,
        ]);
        return new TaskResource($task->load(self::RELATIONS));
    }

    public function update(Task $task, array $requestData): TaskResource
    {
        $task->update($requestData);
        return new TaskResource($task->load(self::RELATIONS)->refresh());
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
