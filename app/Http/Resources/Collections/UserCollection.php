<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *       schema="UserList",
 *       @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(anyOf={@OA\Schema(ref="#/components/schemas/User")})
 *      )
 *  )
 */
class UserCollection extends ResourceCollection
{

    public $collects = UserResource::class;
}
