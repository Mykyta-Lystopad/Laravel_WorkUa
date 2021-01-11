<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrganizationController extends Controller
{
    private $organizationService;

    /**
     * OrganizationController constructor.
     * @param OrganizationService $organizationService
     */
    public function __construct(
        OrganizationService $organizationService
    )
    {
        $this->authorizeResource(Organization::class);
        $this->organizationService = $organizationService;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $organization = $this->organizationService->forIndex();
        return $this->success(OrganizationResource::collection($organization));
    }

    /**
     * @param Organization $organization
     * @return JsonResponse
     */
    public function show(Organization $organization)
    {
        $organizationDetails = $this->organizationService->defForShow($organization);
        return $this->success($organizationDetails);
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
        return $this->created(new OrganizationResource($organization));
    }

    /**
     * @param StoreRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeForEmployers(StoreRequest $request)
    {
        $this->authorize(Organization::class);

        $organization = $this->organizationService->forStoreForEmployers($request);
        return $this->created(new OrganizationResource($organization));
    }

    /**
     * @param UpdateRequest $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        return $this->success(new OrganizationResource($organization));
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
