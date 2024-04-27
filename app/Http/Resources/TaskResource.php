<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *         property="author_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="assignee_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="deadline_at",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="assignee",
 *         type="object",
 *
 *         @OA\Property(
 *             property="id",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="role_id",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="email",
 *             type="string"
 *         )
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="object",
 *
 *         @OA\Property(
 *              property="id",
 *              type="string"
 *          ),
 *          @OA\Property(
 *              property="role_id",
 *              type="string"
 *          ),
 *          @OA\Property(
 *              property="name",
 *              type="string"
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="string"
 *          )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Task"
 *     )
 * )
 */
class TaskResource extends JsonResource
{
}
