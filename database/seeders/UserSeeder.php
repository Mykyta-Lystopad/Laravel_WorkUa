<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->count(5)->create([
            'role' => 'worker'
        ]);
    }
}
