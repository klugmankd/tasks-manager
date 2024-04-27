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

class AuthTest extends TestCase
{

    public function testCanNotLogin(): void
    {
        /* @var User $administrator */
        $administrator = User::factory()->administrator()->create();

        $requestData = [
            'email'    => $administrator->email,
            'password' => fake()->password,
        ];
        $this->post('api/login', $requestData)
            ->assertForbidden();
    }
}
