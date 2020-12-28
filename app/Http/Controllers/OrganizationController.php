<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin')
        {
            return OrganizationResource::collection(Organization::all());
        }
            return OrganizationResource::collection(Organization::where('user_id', $user->id)->get());
    }

    /**
     * @param Organization $organization
     * @return JsonResponse
     */
    public function show(Organization $organization)
    {
            if (request()->workers === '1'){
                $workers = $organization->vacancies()->with('users')->get()->pluck('users')->flatten();
                return $this->success($workers);
            } elseif (request()->vacancies === '1') {
                $vacancyActive = $organization->vacancies->where('status', 'active');
                return $this->success($vacancyActive);
            } elseif (request()->vacancies === '2') {
                $vacancyClosed = $organization->vacancies->where('status', 'closed');
                return $this->success($vacancyClosed);
            }
            elseif (request()->vacancies === '3') {
                $vacanciesAll = $organization->vacancies;
                return $this->success($vacanciesAll);
            } else{
                return $this->success($organization);
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
        $organization = $user->organizations()->create($request->validated());
        return $this->created($organization);
    }

    /**
     * @param StoreRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeForEmployers(StoreRequest $request, User $user)
    {
        $this->authorize('storeForEmployers', Organization::class);

        if ($user->role === 'employer') {
            $organization = $user->organizations()->create($request->validated());

            return $this->created($organization);
        }
        return $this->error('Admin can not creat organization for yourself or workers');
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
        $organization->delete();
        return $this->deleted();
    }

}
