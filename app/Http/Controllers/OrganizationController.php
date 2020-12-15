<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\Http\Resources\OrganizationResourceCollection;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    /**
     * @return OrganizationResourceCollection
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return OrganizationResourceCollection::make(Organization::all());
        } elseif ($user->role === 'employer') {
            $organization = Organization::where('user_id', $user->id)->get();
            return OrganizationResourceCollection::make($organization);
        }


    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === 'admin' || $user->role === 'worker') {
            return response()
                ->json(['success' => false, 'data' => 'Admin or workers can not create organizations'], 403);
        }
        $organization = $user->organizations()->create($request->validated());
        return $this->success($organization, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param StoreRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeForMe(StoreRequest $request, User $user)
    {
        $this->authorize('storeForMe', Organization::class);

        if ($user->role === 'employer') {
            $organization = $user->organizations()->create($request->validated());

            return $this->success($organization, JsonResponse::HTTP_CREATED);
        }
        return response()
            ->json(['success' => false, 'data' => 'Admin can not create organizations for workers or for yourself'], 403);

    }

    /**
     * @param Organization $organization
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Organization $organization, Request $request)
    {
        $vacanciesActive = [];
        $vacanciesClosed = [];
        $vacancy_active = 0;
        $vacancy_closed = 0;
        $vacancies = Vacancy::where('organization_id', $organization->id)->get();
        foreach ($vacancies as $vacancy) {
            $usersAll[] = $vacancy->users;
            if ($vacancy->users->count() < $vacancy->workers_amount) {
                $vacancy_active++;
                $vacanciesActive[] = ($vacancy->withoutRelations());
            } elseif ($vacancy->users->count() == $vacancy->workers_amount) {
                $vacancy_closed++;
                $vacanciesClosed[] = $vacancy->withoutRelations();
            }
        }

        if ($request->vacancies == 1) {
            if ($vacancy_active == 0) {
                return $this->success(['message' => 'You do not have active vacancies'], 200);
            } else {
                return $this->success($vacanciesActive, 200);
            }
        }

        if ($request->vacancies == 2) {
            if ($vacancy_closed == 0) {
                return $this->success(['message' => 'You do not have closed vacancies'], 200);
            } else {
                return $this->success($vacanciesClosed, 200);
            }

        }

        if ($request->vacancies == 3) {
            $vacancies = Vacancy::where('organization_id', $organization->id)->get();
            return $this->success($vacancies, 200);
        }

        if ($request->workers == 1) {
            return $this->success($usersAll, 200);
        }

        if ($request->vacancies == 0) {
            return $this->success($organization, 200);
        }

    }

    /**
     * @param UpdateRequest $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return $this->success($organization, 200);
    }

    /**
     * @param Organization $organization
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Organization $organization)
    {
        $vacancies = Vacancy::where('organization_id', $organization->id)->delete();
        $organization->delete();
        return $this->success(['message' => 'Organization deleted'], JsonResponse::HTTP_NO_CONTENT);
    }

}
