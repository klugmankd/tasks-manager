<?php

namespace App\Http\Requests\Task;

use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Patch(
 *     path="/api/tasks/{id}",
 *     tags={"Tasks"},
 *     summary="Update a task",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the task",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
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
class UpdateRequest extends TaskRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'assignee_id' => ['sometimes', 'integer', 'exists:users,id'],
            'name'        => ['sometimes', 'string'],
            'status'      => ['sometimes', Rule::in(StatusEnum::values())],
            'description' => ['sometimes', 'string'],
            'deadline_at' => ['sometimes', 'date'],
        ];
    }
}
