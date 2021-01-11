<?php


namespace App\Services;

use App\Http\Requests\Organizations\StoreRequest;
use App\Models\Organization;
use App\Models\User;

class OrganizationService
{
    public function forIndex()
    {
        $user = auth()->user();
        if ($user->role === 'admin')
        {
            return Organization::paginate();
        }
        return Organization::where('user_id', $user->id)->paginate();

    }
    public function defForShow(Organization $organization)
    {
        $request = request();
        if ($request->workers === '1'){
            $workers = $organization->vacancies()->with('users')
                ->get()->pluck('users')->flatten();
            $organization->workers = $workers;
//            return $organization;
        } elseif ($request->vacancies === '1') {
            $vacancyActive = $organization
                ->vacancies
                ->where('status', 'active');
            $organization->vacancyAddition = $vacancyActive;
//            return $organization;
        } elseif ($request->vacancies === '2') {
            $vacancyClosed = $organization
                ->vacancies
                ->where('status', 'closed');
            $organization->vacancyAddition = $vacancyClosed;
//            return $organization;
        }
        elseif ($request->vacancies === '3') {
            $vacanciesAll = $organization->vacancies;
            $organization->vacancyAddition = $vacanciesAll;
//            return $organization;
        }
        return $organization;
    }

    public function forStoreForEmployers(StoreRequest $request)
    {
        $user = User::find($request->user_id);

        if ($user->role === 'employer') {
            return $organization = $user->organizations()->create($request->validated());
        }
    }
}
