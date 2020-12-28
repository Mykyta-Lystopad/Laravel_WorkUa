<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
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
        User::where('role', 'employer')
            ->each(function (User $user){
                $organizations = Organization::factory(2)->make();
                $user->organizations()->saveMany($organizations);
        });
    }
}
