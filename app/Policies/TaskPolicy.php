<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{

    use HandlesAuthorization;

    public function readAll(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::TASKS_READ_ALL->value
        );
    }

    public function readOne(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::TASKS_READ_ONE->value
        );
    }

    public function create(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::TASKS_CREATE->value
        );
    }

    public function update(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::TASKS_UPDATE->value
        );
    }

    public function delete(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::TASKS_DELETE->value
        );
    }
}
