<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->search) {
            $user = User::where('country', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%')
                ->orWhere('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->get();
            return $this->success($user, 200);
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->success($user, 200);
    }

    /**
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return $this->success($user, 200);
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $organization = $user->organizations()->get()->pluck('id')
            ->each(function ($organization_id){
                $vacancies = Vacancy::where('organization_id', $organization_id)->get();

                foreach ($vacancies as $vacancy)
                {
                    $usersAll = $vacancy->users;
                    $vacancy->users()->detach($usersAll);
                }
                $vacancies = Vacancy::where('organization_id', $organization_id)->delete();
        });

        $user->organizations()->delete();

        $user->delete();

        return $this->success(['message' => 'User ' . $user->first_name . ' SoftDeleted'], 204);
    }
}
