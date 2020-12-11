<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        /**@var User $user */
        $user = User::create($request->validated());
        $data = UserResource::make($user)->toArray($request) +
            ['access_token'=>$user->createToken('api')->plainTextToken];
        return $this->created($data);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /**@var User $user */
        if (!auth()->once($request->validated()))
        {
            throw ValidationException::withMessages([ // false - 'Wrong email or password'
                'email'=> 'Wrong email or password'
            ]);
        }
        $user = auth()->user();
        $data = UserResource::make($user)->toArray($request) +
            ['access_token' => $user->createToken('api')->plainTextToken];
        return $this->success($data, 200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return $this->success('successfully logged out');
    }
}
