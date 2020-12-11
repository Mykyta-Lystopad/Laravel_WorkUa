<?php

namespace App\Providers;

use App\Http\Controllers\StatsController;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use App\Policies\OrganizationPolicy;
use App\Policies\StatsPolicy;
use App\Policies\UserPolicy;
use App\Policies\VacancyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Organization' => 'App\Policies\OrganizationPolicy',
//        User::class => UserPolicy::class,
//        Organization::class => OrganizationPolicy::class,
//        Vacancy::class => VacancyPolicy::class,
//        StatsController::class => StatsPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
