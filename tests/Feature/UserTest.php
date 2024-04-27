<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{

    private const array ITEM_STRUCTURE = [
        'id',
        'name',
        'email',
        'role_id',
    ];

    public function testIndexAssignees(): void
    {
        $administrator = User::factory()->administrator()->create();
        $count = 10;
        User::factory($count)->administrator()->create();
        User::factory($count)->assignee()->create();

        $assigneeRole = Role::firstWhere('name', RoleEnum::ASSIGNEE);
        $assigneesCount = User::where('role_id', $assigneeRole->id)->count();
        $this->actingAs($administrator);
        $this->get('api/users')
            ->assertOk()
            ->assertJsonCount($assigneesCount, 'data')
            ->assertJsonStructure(['data' => [
                '*' => self::ITEM_STRUCTURE,
            ]]);
    }

    public function testCanNotIndexAssignees(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::USERS_READ_ALL->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);

        $this->actingAs($author);
        $this->get('api/users')
            ->assertForbidden();
    }
}
