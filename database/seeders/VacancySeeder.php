<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::all()->each(function (Organization $organization) {
            $vacancy = Vacancy::factory(4)->make();
            $organization->vacancies()->saveMany($vacancy);

        });

        Vacancy::all()->each(function (Vacancy $vacancy) {
            $users = User::where('role', 'worker')
                ->inRandomOrder()
                ->take(rand(3,5))
                ->get();

            $vacancy->users()->attach($users);
        });
    }
}
