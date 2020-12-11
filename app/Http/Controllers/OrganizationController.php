<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $organization = Organization::with('vacancies')->get();
        return response()->json($organization);
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var User  $user */
        $user = auth()->user();
        $organization = $user->organizations()->create($request->validated());
            return response()->json($organization, 201);
    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Organization $organization)
    {
        $organization->load('vacancies');
        return response()->json($organization);
    }

    /**
     * @param UpdateRequest $request
     * @param Organization $organization
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return response()->json($organization);
    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Organization $organization)
    {
        $vacancies = Vacancy::where('organization_id', $organization->id)->delete();
        $organization->delete();

        return response()->json(['message' => 'Organization deleted']);
    }

}
