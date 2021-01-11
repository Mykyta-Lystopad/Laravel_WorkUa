<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    private $userServise;

    public function __construct(UserService $userService)
    {
        $this->userServise = $userService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        /**@var User $user */
        $user = User::create($request->validated() +
            ['verify_code' => mt_rand(100000, 999999)]
        );

//        Mail::to($user->email)->send(new VerifyEmail($user));

        $user->notify(new EmailVerification($user));

        return response()->json(['success' => 'Check your email and click on verify link']);
//        $data = UserResource::make($user)->toArray($request) +
//            ['access_token'=>$user->createToken('api')->plainTextToken];
//        return $this->created($data);
    }

    public function resendVerificationEmail($email)
    {
        $user_id = User::whereEmail($email)->get()->pluck('id')->get(0);
        if (!$user_id){
            return $this->error(['message' => 'Email does not exist']);
        }
        $user = User::find($user_id);
        if ($user->email_verified_at != null){
            return response()->json(['success' => 'Your email already verified']);
        }
        $user->update([
            $user->verify_code = mt_rand(100000, 999999)
        ]);

        $user->notify(new EmailVerification($user));
        return $this->success('Check your email');
    }

    public function resendPassword($email)
    {
        $user_id = User::whereEmail($email)->get()->pluck('id')->get(0);
        if (!$user_id){
            return $this->error(['message' => 'Email does not exist']);
        }
        $user = User::find($user_id);
        $password = (mt_rand(10, 99)) . (Str::random(8)) . (mt_rand(10, 99));
        $user->update([
            $user->verify_code = $password,
            $user->password = $password
        ]);

        $user->notify(new EmailVerification($user));

        $user->update([
            $user->verify_code = 'done',
            $user->email_verified_at = now()
        ]);
        return $this->success( 'Check your email with new password');
    }
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /**@var User $user */
        if (!auth()->once($request->validated())) {
            throw ValidationException::withMessages([
                'email' => 'Wrong email or password'
            ]);
        }

        $user = $this->userServise->userEmailVerify();

        return $this->success($user);
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
