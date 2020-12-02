<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequestRequest;
use App\Http\Requests\RegisterRequestRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;

/**
 * Class AuthController
 * @package App\Http\Controllers
 * @throws Exception
 */
class AuthController extends Controller
{
    /**
     * @param RegisterRequestRequest $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(RegisterRequestRequest $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        /**@var User $user */
        $user = User::create($request->validated());
        $data = UserResource::make($user)->toArray($request) +
            ['access_token'=>$user->createToken('api')->plainTextToken];
        return $this->created($data);
//        return response()->json($data, 201);
    }

    /**
     * @param LoginRequestRequest $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(LoginRequestRequest $request): \Symfony\Component\HttpFoundation\JsonResponse
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
        return $this->success($data);
    }

    public function logout(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        /**@var User $user
         *@return bool|null
         * @return \Illuminate\Http\RedirectResponse
         * @throws \Exception
         * @return bool|null
         */
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return $this->success('successfully logged out');
    }
}
