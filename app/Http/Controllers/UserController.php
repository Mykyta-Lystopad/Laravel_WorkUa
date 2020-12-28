<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        if (request()->search) {
            $user = User::where('country', 'like', '%' . request()->search . '%')
                ->orWhere('city', 'like', '%' . request()->search . '%')
                ->orWhere('first_name', 'like', '%' . request()->search . '%')
                ->orWhere('last_name', 'like', '%' . request()->search . '%')
                ->orWhere('country', 'like', '%' . request()->search . '%')
                ->get();
            return UserResource::collection($user);
        }
        $user = User::all();
        return UserResource::collection($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return  $this->success($user);
    }

    /**
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return  $this->success($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->deleted();
    }
}
