<?php

namespace Database\Factories;

use App\Models\User;
use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;
use phpDocumentor\Reflection\Types\Integer;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'verify_code' => 'done',
            'password' => 123456,
            'country' => 'Україна',
            'city' => $this->faker->city,
            'telephone' => $this->faker->phoneNumber,
            'role' => 'worker'
        ];
    }
}
