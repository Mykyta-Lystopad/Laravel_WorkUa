<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
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

        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['success' => 'Check your email and click on verify link']);
//        $data = UserResource::make($user)->toArray($request) +
//            ['access_token'=>$user->createToken('api')->plainTextToken];
//        return $this->created($data);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /**@var User $user */
        if (!auth()->once($request->validated())) {
            throw ValidationException::withMessages([ // false - 'Wrong email or password'
                'email' => 'Wrong email or password'
            ]);
        }

        $user = auth()->user();

        if ($user->verify_status == 'waiting') {
            $this->updateOnce();
        }
        $data = UserResource::make($user)->toArray($request) +
            ['access_token' => $user->createToken('api')->plainTextToken];

        $this->update($user, $data);
        return $this->success($data);
    }

    public function update($user, $data)
    {
        foreach ($data as $value) {
            $arr[] = $value;
        }
        $user->update([
            $user->remember_token = $arr[12]
        ]);

    }

    private function updateOnce()
    {
        $user = auth()->user();
        $user->update([
            $user->verify_status = 'active',
            $user->email_verified_at = now(),
        ]);
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
