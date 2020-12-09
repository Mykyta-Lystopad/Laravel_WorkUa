<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
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
        $userWorker = User::where('role',  'worker')->count();
        $userEmployer = User::where('role', 'employer')->count();
        $userDel = User::onlyTrashed()->count();
        $data = [
            "Admin"=> $userAdm,
            "Employer"=> $userEmployer,
            "Worker"=> $userWorker,
            "Soft-Deleted"=> $userDel,
            "All"=> $user
        ];

        return response()->json(['Statistics of users'=> $data]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function vacancies()
    {
        $this->authorize('vacancies', StatsController::class);

        $vacancies_all = Vacancy::withTrashed()->count();
        $vacancies_active = Vacancy::withTrashed()->where('status', 1)->count();
        $vacancies_closed = Vacancy::withTrashed()->where('status', 0)->count();
        $vacancies_del = Vacancy::onlyTrashed()->count();
        $data = [
          'Active'=> $vacancies_active,
          'Closed'=> $vacancies_closed,
          'Soft-Deleted'=> $vacancies_del,
          'All'=> $vacancies_all
        ];

        return response()->json(['Statistic of vacancies: '=> $data]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function organizations()
    {
        $this->authorize('organizations', StatsController::class);

        $organizationsActive = Organization::withTrashed()->count();
        $organizationsDel = Organization::onlyTrashed()->count();
        $organizationsAll = Organization::withTrashed()->count();
        $data = [
            "Active"=> $organizationsActive,
            "Soft-Deleted"=> $organizationsDel,
            "All"=> $organizationsAll
        ];

        return response()->json(['Statistic of organizations'=>$data]);
    }


}
