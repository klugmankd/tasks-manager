<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken(env('API_BEARER_TOKEN'))->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
