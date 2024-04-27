<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Http\Resources\Collections\UserCollection;
use App\Models\Role;
use App\Models\User;

class UserService
{

    public function readAllAssignees(): UserCollection
    {
        $assigneeRole = Role::firstWhere('name', RoleEnum::ASSIGNEE->value);
        $users = User::where('role_id', $assigneeRole->id)->get();
        return new UserCollection($users);
    }
}
