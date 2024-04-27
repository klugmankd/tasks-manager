<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\GetAllRequest;
use App\Http\Resources\Collections\UserCollection;
use App\Services\UserService;

class UserController
{

    public function __construct(private UserService $service)
    {}

    public function index(GetAllRequest $request): UserCollection
    {
        return $this->service->readAllAssignees();
    }
}
