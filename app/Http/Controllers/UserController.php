<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->authorizeResource(User::class);
        $this->userService = $userService;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->userService->searchUsers();

        return $this->success(UserResource::collection($users));
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    /**
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return $this->success(new UserResource($user));
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
