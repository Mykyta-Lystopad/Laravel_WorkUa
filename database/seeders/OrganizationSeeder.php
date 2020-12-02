<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user){
            if ($user['role'] == 'employer') {
                $oranizations = Organization::factory(2)->make();

                $user->organizations()->saveMany($oranizations);
            }
        });
    }
}
