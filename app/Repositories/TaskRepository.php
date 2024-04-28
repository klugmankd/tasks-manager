<?php

namespace App\Repositories;

use App\DataTransferObjects\TaskDTO;
use App\Enums\RoleEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class TaskRepository
{

    public const array RELATIONS = ['assignee', 'author'];

    public function readAll(User $user): Collection
    {
        return Task::with(self::RELATIONS)
            ->when($user->role->name === RoleEnum::ASSIGNEE->value, function ($query) use ($user) {
                return $query->where('assignee_id', $user->id);
            })
            ->get();
    }

    public function create(User $user, TaskDTO $taskDTO): Task
    {
        $task = Task::create([
            'author_id' => $user->id,
            ...$taskDTO->toRepositoryArray(),
        ]);

        return $task->load(self::RELATIONS);
    }

    public function update(Task $task, TaskDTO $taskDTO): Task
    {
        $task->update($taskDTO->toRepositoryArray());
        return $task->load(self::RELATIONS)->refresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
