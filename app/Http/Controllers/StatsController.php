<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthorizationException
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
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function vacancies()
    {
        $this->authorize('vacancies', StatsController::class);

        $vacancies_active = Vacancy::all()->where('status', '=','active')->count();
        $vacancies_closed = Vacancy::all()->where('status', '=','closed')->count();
        $vacancies_del = Vacancy::onlyTrashed()->count();
        $vacancies_all = Vacancy::withTrashed()->count();
        $data = [
            'Active' => $vacancies_active,
            'Closed' => $vacancies_closed,
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
