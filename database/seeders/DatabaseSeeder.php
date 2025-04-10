<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Appointment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(10)->create(); // Creates 10 users (patients and doctors)
        DoctorProfile::factory(5)->create(); // 5 doctors
        PatientProfile::factory(10)->create(); // 10 patients
        Appointment::factory(15)->create(); // 15 appointments
    }
}
