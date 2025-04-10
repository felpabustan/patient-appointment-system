<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DoctorProfile;
use App\Models\User; // Make sure to import the User model
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorProfile>
 */
class DoctorProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'doctor'])->id,
            'specialty' => $this->faker->word,  // Use $this->faker to access faker instance
            'phone' => $this->faker->phoneNumber,
            'availability' => json_encode([
                'monday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
                'wednesday' => ['9:00 AM - 12:00 PM'],
                'friday' => ['9:00 AM - 1:00 PM'],
            ]),
        ];
    }
}
