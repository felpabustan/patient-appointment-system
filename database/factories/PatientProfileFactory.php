<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PatientProfile;
use App\Models\User; // Make sure to import the User model
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientProfile>
 */
class PatientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'patient'])->id,  // Create a user with 'patient' role
            'dob' => $this->faker->date(),  // Use $this->faker to access the Faker instance
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}