<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /* @var Role $administrator */
        $administrator = Role::firstWhere('name', RoleEnum::ADMINISTRATOR->value);
        $administratorsPermissions = Permission::all('id')->pluck('id')->toArray();

        $assigneesPermissions = PermissionEnum::getByRole(RoleEnum::ASSIGNEE);
        /* @var Role $assignee */
        $assignee = Role::firstWhere('name', RoleEnum::ASSIGNEE->value);
        $assigneesPermissions = Permission::whereIn('name', $assigneesPermissions)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        DB::table('roles_permissions')
            ->insert([
                ...array_map(fn($item) => [
                    'role_id'       => $administrator->id,
                    'permission_id' => $item,
                ], $administratorsPermissions),
                ...array_map(fn($item) => [
                    'role_id'       => $assignee->id,
                    'permission_id' => $item,
                ], $assigneesPermissions)
            ]);
    }
}
