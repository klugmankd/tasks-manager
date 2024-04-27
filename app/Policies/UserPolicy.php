<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;

    public function readAll(User $user): bool
    {
        return $user->role->permissions->contains(
            fn(Permission $permission) => $permission->name === PermissionEnum::USERS_READ_ALL->value
        );
    }
}
