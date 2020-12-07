<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\StoreOrganizationRequest;
use App\Http\Requests\Organizations\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $organization = Organization::all();
        $organization = Organization::with('vacancies')->get();
        return response()->json($organization);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexWeb(Request $request)
    {
        if ($request->search){
            $organizations = Organization::join('vacancies', 'organization_id', '=', 'organizations.id')
                ->where('name', 'like', '%' . $request->search .  '%')
                ->orderBy('organizations.created_at')
                ->get('organizations.id');
//            dd($organizations);
            return view('organization.indexWeb', compact('organizations'));
        }
        $organizations = Organization::all();
//                ->orderBy('organization.created_at')
//                ->paginate(4);
        return view('organization.indexWeb', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWeb()
    {
        return view('organization.createWeb');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganizationRequest $request)
    {
//        $this->authorize('create', Organization::class);
        /** @var User  $user */
        $user = auth()->user();
        $organization = $user->organizations()->create($request->validated());
//        $organization = Organization::create($request->validated());
//            $organization = new Organization();
//            $organization->orgName = $request->orgName;
//            $organization->country = $request->country;
//            $organization->city = $request->city;
//            $organization->user_id = $user->id;
//            $organization->save();
            return response()->json($organization, 201);
    }

    /**
     * @param StoreOrganizationRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeWeb(StoreOrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());

        return redirect()->route('organization.indexWeb')->with('success', 'Організацію створено');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $organization->load('vacancies');
        return response()->json($organization);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return response()->json($organization);
    }

    /**
     * Remove the specified resource from storage.
     * @param Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
            $organization->delete();

            return response()->json(['message' => 'object ' . $organization->id . ' deleted']);
    }

//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Organization  $organization
//     * @return mixed
//     */
//    public function restore(User $user, Organization $organization)
//    {
//        $organization->restore();
//
//        return response()->json(['organization '. $organization->id .'restored'], $organization);
//    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @return bool|null
     */
    public function destroyWeb($id)
    {
        $organization = Organization::find($id);
        $organization->delete();

        return redirect()->route('organization.indexWeb')->with('success', 'Організацію видалено');
    }
}
