<?php

namespace App\Http\Requests\Task;

use App\DataTransferObjects\TaskDTO;
use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/tasks",
 *     tags={"Tasks"},
 *     summary="Create a new task",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Task data",
 *         @OA\JsonContent(
 *             required={"assignee_id", "name", "status", "description", "deadline_at"},
 *             @OA\Property(
 *                 property="assignee_id",
 *                 type="integer",
 *                 description="ID of the assignee user",
 *                 example=1
 *             ),
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="Name of the task"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 description="Status of the task",
 *                 enum={"Open", "In Progress", "Ready", "Done"}
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Description of the task"
 *             ),
 *             @OA\Property(
 *                 property="deadline_at",
 *                 type="string",
 *                 format="date",
 *                 description="Deadline date of the task (YYYY-MM-DD)"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Access denied"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class StoreRequest extends TaskRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'name'        => ['required', 'string'],
            'status'      => ['required', Rule::in(StatusEnum::values())],
            'description' => ['required', 'string'],
            'deadline_at' => ['required', 'date'],
        ];
    }
}
