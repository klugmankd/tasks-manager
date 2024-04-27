<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/tasks/{id}",
 *     tags={"Tasks"},
 *     summary="Getting Task By Id",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the task",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Task successfully got",
 *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
 *     )
 * )
 */
class GetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('readOne', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
