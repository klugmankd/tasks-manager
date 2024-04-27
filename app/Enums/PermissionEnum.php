<?php

namespace App\Enums;

enum PermissionEnum: string
{

    case USERS_READ_ALL = 'users.read_all';
    case TASKS_READ_ALL = 'tasks.read_all';
    case TASKS_READ_ONE = 'tasks.read_one';
    case TASKS_CREATE = 'tasks.create';
    case TASKS_UPDATE = 'tasks.update';
    case TASKS_DELETE = 'tasks.delete';

    public static function getByRole(RoleEnum $role): array
    {
        if ($role === RoleEnum::ASSIGNEE) {
            return [
                self::TASKS_READ_ALL->value,
                self::TASKS_READ_ONE->value,
            ];
        }

        return array_map(fn(PermissionEnum $case) => $case->value, self::cases());
    }
}
