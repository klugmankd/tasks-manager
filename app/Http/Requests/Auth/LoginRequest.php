<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Get Bearer Token",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User data",
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 description="User email",
 *                 example="test@exapmple.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 description="User password"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User logined successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="token",
 *                 type="string",
 *             )
 *         )
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
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $credentials = $this->only('email', 'password');
        return Auth::attempt($credentials);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
