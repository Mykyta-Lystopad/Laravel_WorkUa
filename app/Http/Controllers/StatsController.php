<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function users()
    {
        $this->authorize('users', StatsController::class);
        /** @var  $user */
        $user = User::withTrashed()->count();
        $userAdm = User::where('role', 'admin')->count();
        $userWorker = User::where('role', 'worker')->count();
        $userEmployer = User::where('role', 'employer')->count();
        $userDel = User::onlyTrashed()->count();
        $data = [
            "Admin" => $userAdm,
            "Employer" => $userEmployer,
            "Worker" => $userWorker,
            "Soft-Deleted" => $userDel,
            "All" => $user
        ];

        return response()->json(['Statistics of users' => $data]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function vacancies()
    {
        $this->authorize('vacancies', StatsController::class);
        $vacancies_active = Vacancy::with('users')->get();
        $active =  0;
        $close =  0;
        foreach ($vacancies_active as $vacancy)
        {
            if ($vacancy->users->count() < $vacancy->workers_amount)
                {
                    $active++;
                }
            if ($vacancy->users->count() == $vacancy->workers_amount)
                {
                    $close++;
                }

        }
        $vacancies_del = Vacancy::onlyTrashed()->count();
        $vacancies_all = Vacancy::withTrashed()->count();
        $data = [
            'Active' => $active,
            'Closed' => $close,
            'Soft-Deleted' => $vacancies_del,
            'All' => $vacancies_all
        ];

        return response()->json(['Statistic of vacancies: ' => $data]);


    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function organizations()
    {
        $this->authorize('organizations', StatsController::class);

        $organizationsActive = Organization::withTrashed()->count();
        $organizationsDel = Organization::onlyTrashed()->count();

        $data = [
            "Active" => $organizationsActive,
            "Soft-Deleted" => $organizationsDel,
            "All" => $organizationsActive + $organizationsDel
        ];

        return response()->json(['Statistic of organizations' => $data]);
    }


}
