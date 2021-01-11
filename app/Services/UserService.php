<?php


namespace App\Services;


use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{

    public function searchUsers()
    {
        if (request()->search) {
            return $users = User::where('first_name', 'like', '%' . request()->search . '%')
                ->orWhere('last_name', 'like', '%' . request()->search. '%')
                ->orWhere('country', 'like', '%' . request()->search . '%')
                ->orWhere('city', 'like', '%' . request()->search . '%')
                ->orWhere('role', 'like', '%' . request()->search . '%')
                ->paginate();
        }
        else{
            return $users = User::paginate();
            }
    }

    public function userEmailVerify()
    {
        $user = auth()->user();
        $request = request();
        if ($user->email_verified_at == null) {
            if ($user->verify_code == $request->verify_code) {
                 $user->update([
                    $user->email_verified_at = now(),
                    $user->verify_code = 'done'
                ]);

            } else {
                return response()->json('Check email or resend the letter');
            }
        }

        $data = UserResource::make($user)->toArray($request) +
            ['access_token' => $user->createToken('api')->plainTextToken];

        return $data;
    }
}
