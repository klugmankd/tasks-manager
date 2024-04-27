<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *       schema="TaskList",
 *       @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(anyOf={@OA\Schema(ref="#/components/schemas/Task")})
 *      )
 *  )
 */
class TaskCollection extends ResourceCollection
{

    public $collects = TaskResource::class;
}
