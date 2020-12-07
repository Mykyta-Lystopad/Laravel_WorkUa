<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@email.com'
        ]);
        User::factory()->count(5)->create([
            'role' => 'employer'
        ]);
        User::factory()->count(35)->create([
            'role' => 'worker'
        ]);

//        Vacancy::all()->each(function (Vacancy $vacancy) {
//            $user = User::all()->random(3);
//            $vacancy->users()->attach($user);
//        });
    }




}
