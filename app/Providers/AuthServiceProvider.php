<?php

namespace App\Providers;

use App\Http\Controllers\StatsController;
use App\Models\Organization;
use App\Models\Vacancy;
use App\Policies\OrganizationPolicy;
use App\Policies\StatsPolicy;
use App\Policies\VacancyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Organization' => 'App\Policies\OrganizationPolicy',
        Organization::class => OrganizationPolicy::class,
        Vacancy::class => VacancyPolicy::class,
        StatsController::class => StatsPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
//        Gate::allows('user-can-viewAny', Organization::class);
    }
}
