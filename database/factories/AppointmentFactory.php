<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => User::factory()->create(['role' => 'doctor'])->id,
            'patient_id' => User::factory()->create(['role' => 'patient'])->id,
            'date' => $this->faker->date(),
            'time_slot' => $this->faker->time('H:i') . ' - ' . $this->faker->time('H:i'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => $this->faker->sentence,
        ];
    }
}
