<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\Permission;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskTest extends TestCase
{

    private const array ITEM_STRUCTURE = [
        'id',
        'author_id',
        'assignee_id',
        'name',
        'status',
        'description',
        'deadline_at',
        'created_at',
        'updated_at',
        'assignee',
        'author',
    ];

    public function testCreateTask(): void
    {
        $author = User::factory()->administrator()->create();
        $assignee = User::factory()->assignee()->create();

        $requestData = [
            'assignee_id' => $assignee->id,
            'name'        => fake()->realText(20),
            'status'      => StatusEnum::OPEN->value,
            'description' => fake()->realText(),
            'deadline_at' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i'),
        ];

        $this->actingAs($author);
        $result = $this->post('api/tasks', $requestData)
            ->assertCreated();

        $response = $result->json();
        foreach ($requestData as $key => $value) {
            $this->assertEquals($value, $response['data'][$key]);
        }
    }

    public function testCanNotCreateTask(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::TASKS_CREATE->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);

        $this->actingAs($author);
        $this->post('api/tasks')
            ->assertForbidden();
    }

    #[DataProvider('updateFieldsDataProvider')]
    public function testUpdateTask(string $field): void
    {
        $author = User::factory()->administrator()->create();
        $assignee = User::factory()->assignee()->create();

        $task = Task::factory()->create([
            'author_id'   => $author->id,
            'assignee_id' => $assignee->id,
        ]);

        $fieldValuesCallbacks = [
            'assignee_id' => function() {
                /* @var User $assignee */
                $assignee = User::factory()->assignee()->create();
                return $assignee->id;
            },
            'name'        => fn() => fake()->realText(20),
            'status'      => fn() => StatusEnum::randomUnique()->value,
            'description' => fn() => fake()->realText(),
            'deadline_at' => fn() => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ];

        $fieldValueCallback = $fieldValuesCallbacks[$field];
        $fieldValue = $fieldValueCallback();
        $requestData = [];
        $requestData[$field] = $fieldValue;

        $this->actingAs($author);
        $result = $this->patch('api/tasks/' . $task->id, $requestData)
            ->assertOk();

        $response = $result->json();
        foreach ($requestData as $key => $value) {
            $this->assertEquals($value, $response['data'][$key]);
        }
    }

    public function testCanNotUpdateTask(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::TASKS_UPDATE->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);
        $task = Task::factory()->create(['author_id' => $author->id]);

        $this->actingAs($author);
        $this->patch('api/tasks/' . $task->id)
            ->assertForbidden();
    }

    public function testIndexByAdministrator(): void
    {
        $administrator = User::factory()->administrator()->create();
        $count = 10;
        Task::factory($count)->create(['author_id' => $administrator]);
        Task::factory($count)->create();

        $this->actingAs($administrator);
        $this->get('api/tasks')
            ->assertOk()
            ->assertJsonCount(Task::count(), 'data')
            ->assertJsonStructure(['data' => [
                '*' => self::ITEM_STRUCTURE,
            ]]);
    }

    public function testCanNotIndexTask(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::TASKS_READ_ALL->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);

        $this->actingAs($author);
        $this->get('api/tasks')
            ->assertForbidden();
    }

    public function testIndexByAssignee(): void
    {
        /* @var User $assignee */
        $assignee = User::factory()->assignee()->create();
        Task::factory(10)->create(['assignee_id' => $assignee->id]);

        $tasksCount = Task::where('assignee_id', $assignee->id)->count();
        $this->actingAs($assignee);
        $this->get('api/tasks')
            ->assertOk()
            ->assertJsonCount($tasksCount, 'data')
            ->assertJsonStructure(['data' => [
                '*' => self::ITEM_STRUCTURE,
            ]]);
    }

    #[DataProvider('rolesDataProvider')]
    public function testShowByUserRole(string $role): void
    {
        /* @var User $user */
        $user = User::factory()->{$role}()->create();
        if ($role === RoleEnum::ASSIGNEE->value) {
            $tasks = Task::factory(10)->create(['assignee_id' => $user->id]);
        } else {
            $tasks = Task::factory(10)->create();
        }
        $randomTaskIndex = fake()->numberBetween(0, 9);
        /* @var Task $task */
        $task = $tasks[$randomTaskIndex];

        $this->actingAs($user);
        $result = $this->get('api/tasks/' . $task->id);

        $response = $result->json();
        $this->assertShow($task, $response);
    }

    public function testCanNotShowTask(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::TASKS_READ_ONE->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);
        $task = Task::factory()->create(['author_id' => $author->id]);

        $this->actingAs($author);
        $this->get('api/tasks/' . $task->id)
            ->assertForbidden();
    }

    public function testDelete(): void
    {
        /* @var User $user */
        $user = User::factory()->administrator()->create();
        $task = Task::factory()->create(['author_id' => $user->id]);

        $this->actingAs($user);
        $this->delete('api/tasks/' . $task->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function testCanNotDeleteTask(): void
    {
        $permission = Permission::firstWhere('name', PermissionEnum::TASKS_DELETE->value);
        /* @var User $author */
        $author = User::factory()->administrator()->create();
        $author->role->permissions()->detach([$permission->id]);
        $task = Task::factory()->create(['author_id' => $author->id]);

        $this->actingAs($author);
        $this->delete('api/tasks/' . $task->id)
            ->assertForbidden();
    }

    private function assertShow(Task $expectedTask, array $response): void
    {
        foreach ($response['data'] as $key => $value) {
            if ($expectedTask->{$key} instanceof Carbon) {
                $this->assertEquals($expectedTask->{$key}->format('Y-m-d H:i'), $value);
                continue;
            }

            if (is_object($expectedTask->{$key})) {
                $this->assertEquals($expectedTask->{$key}->id, $value['id']);
                $this->assertEquals($expectedTask->{$key}->role_id, $value['role_id']);
                $this->assertEquals($expectedTask->{$key}->name, $value['name']);
                $this->assertEquals($expectedTask->{$key}->email, $value['email']);
                continue;
            }

            $this->assertEquals($expectedTask->{$key}, $value);
        }
    }

    public static function rolesDataProvider(): array
    {
        return [
            [RoleEnum::ADMINISTRATOR->value],
            [RoleEnum::ASSIGNEE->value],
        ];
    }

    public static function updateFieldsDataProvider(): array
    {
        return [
            ['assignee_id'],
            ['name'],
            ['status'],
            ['description'],
            ['deadline_at'],
        ];
    }

}
