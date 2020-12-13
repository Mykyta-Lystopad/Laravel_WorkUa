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
//        Vacancy::factory(10)->create()
//            ->each(function (Vacancy $vacancy){
//                $organizations = Organization::inRandomOrder()->get()->take(4);
//                $vacancy->organizations()->attach($organizations);
//            });

        Organization::all()->each(function (Organization $organization) {
            $vacancy = Vacancy::factory(4)->make();
            $organization->vacancies()->saveMany($vacancy);

        });

        Vacancy::all()->each(function (Vacancy $vacancy) {
            $users = User::all()->random(5)->except([2,3,4,5,6]);

            $vacancy->users()->attach($users);
        });


    }
}
