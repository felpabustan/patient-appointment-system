<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    private array $statusOptions;

    public function __construct()
    {
        $this->statusOptions = Appointment::getStatusOptions();
    }

    public function index()
    {
        $user = auth()->user();
        
        $query = Appointment::with(['doctor', 'patient']);

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->latest()->paginate(20);

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')
            ->select('id', 'name', 'email')
            ->get();
            
        $patients = User::where('role', 'patient')
            ->select('id', 'name', 'email')
            ->get();

        $existingAppointments = Appointment::select('id', 'doctor_id', 'date', 'time_slot')
            ->where('status', '!=', 'cancelled')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->get();

        return Inertia::render('Appointments/Create', [
            'doctors' => $doctors,
            'patients' => $patients,
            'statuses' => $this->statusOptions,
            'auth' => [
                'user' => auth()->user()
            ],
            'existingAppointments' => $existingAppointments
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::create($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show($id)
    {
        $appointment = Appointment::with(['doctor', 'patient'])
            ->findOrFail($id);

        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $doctors = User::where('role', 'doctor')
            ->select('id', 'name', 'email')
            ->get();
            
        $patients = User::where('role', 'patient')
            ->select('id', 'name', 'email')
            ->get();

        $existingAppointments = Appointment::select('id', 'doctor_id', 'date', 'time_slot')
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->get();

        return Inertia::render('Appointments/Edit', [
            'doctors' => $doctors,
            'patients' => $patients,
            'statuses' => $this->statusOptions,
            'appointment' => $appointment->load(['doctor', 'patient']),
            'existingAppointments' => $existingAppointments,
            'userRole' => auth()->user()->role
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = auth()->user();

        // If user is a doctor, only allow updating status and notes
        if ($user->role === 'doctor') {
            $validated = $request->validate([
                'status' => [
                    'required',
                    'in:' . implode(',', $this->statusOptions),
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value === 'completed' && 
                            Carbon::parse($request->date)->isAfter(now())) {
                            $fail('Cannot mark future appointments as completed.');
                        }
                    },
                ],
                'notes' => 'nullable|string|max:1000',
            ]);

            // Merge with existing appointment data to maintain other fields
            $validated = array_merge(
                $appointment->only(['doctor_id', 'patient_id', 'date', 'time_slot']),
                $validated
            );
        } else {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'patient_id' => 'required|exists:users,id',
                'date' => 'required|date|after_or_equal:today',
                'time_slot' => 'required|string',
                'status' => [
                    'required',
                    'in:' . implode(',', $this->statusOptions),
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value === 'completed' && 
                            Carbon::parse($request->date)->isAfter(now())) {
                            $fail('Cannot mark future appointments as completed.');
                        }
                    },
                ],
                'notes' => 'nullable|string|max:1000',
            ]);
        }

        $appointment->update($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
