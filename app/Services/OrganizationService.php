<?php


namespace App\Services;

use App\Models\Organization;

class OrganizationService
{
    public function defForShow($request, Organization $organization)
    {
        if ($request->workers === '1'){
            $workers = $organization->vacancies()->with('users')->get()->pluck('users')->flatten();
            return response()->json($workers);
        } elseif ($request->vacancies === '1') {
            $vacancyActive = $organization->vacancies->where('status', 'active');
            return response()->json($vacancyActive);
        } elseif ($request->vacancies === '2') {
            $vacancyClosed = $organization->vacancies->where('status', 'closed');
            return response()->json($vacancyClosed);
        }
        elseif ($request->vacancies === '3') {
            $vacanciesAll = $organization->vacancies;
            return response()->json($vacanciesAll);
        } else{
            return response()->json($organization);
        }
    }
}
