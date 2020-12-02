<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function vacancies()
    {

    }

    public function organizations()
    {
        $organizationsActive = Organization::count();
        $organizationsDel = Organization::onlyTrashed()->count();
        $organizationsAll = Organization::withTrashed()->count();
        $data = [
            "Active"=> $organizationsActive,
            "Deleted"=> $organizationsDel,
            "All"=> $organizationsAll
        ];

        return response()->json(['Statistic of organizations'=>$data]);
    }

    public function users()
    {
        /** @var  $user */
        $user = User::count();
        $userAdm = User::where('role', 'admin')->count();
        $userWorker = User::where('role',  'worker')->count();
        $userEmployer = User::where('role', 'employer')->count();
        $data = [
          "admin"=> $userAdm,
          "employer"=> $userEmployer,
          "worker"=> $userWorker,
          "all"=> $user
        ];

        return response()->json(['Statistics of users'=> $data]);
    }
}
