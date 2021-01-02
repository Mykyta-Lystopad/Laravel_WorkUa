<?php


namespace App\Services;


use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    public function searchDef($request)
    {
        $user = User::where('country', 'like', '%' . $request->search . '%')
            ->orWhere('city', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->orWhere('country', 'like', '%' . $request->search . '%')
            ->get();
        return UserResource::collection($user);
    }
}
