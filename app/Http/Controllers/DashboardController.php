<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->role === 'doctor') {
            $appointments = Appointment::where('doctor_id', $user->id)
                ->selectRaw('
                    status,
                    COUNT(*) as count
                ')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $uniquePatients = Appointment::where('doctor_id', $user->id)
                ->distinct('patient_id')
                ->count('patient_id');

            $stats = [
                'confirmedAppointments' => $appointments['confirmed'] ?? 0,
                'completedAppointments' => $appointments['completed'] ?? 0,
                'totalPatients' => $uniquePatients
            ];
        } else {
            $appointments = Appointment::selectRaw('
                status,
                COUNT(*) as count
            ')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

            $counts = [
                'doctors' => DoctorProfile::count(),
                'patients' => PatientProfile::count()
            ];

            $stats = [
                'confirmedAppointments' => $appointments['confirmed'] ?? 0,
                'totalDoctors' => $counts['doctors'],
                'totalPatients' => $counts['patients']
            ];
        }
        
        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}
