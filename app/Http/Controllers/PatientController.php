<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile as Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->latest()->paginate(20);

        return Inertia::render('Patients/Index', [
            'patients' => $patients,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')
            ->where('role', '!=', 'patient')
            ->where('role', '!=', 'admin')
            ->get();

        return Inertia::render('Patients/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:patient_profiles,user_id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
        ]);

        $patient = Patient::create($validated);

        $user = User::findOrFail($validated['user_id']);
        $user->update(['role' => 'patient']);

        return redirect()->route('patients.index')->with('success', 'Patient created successfully.');
    }

    public function show($id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        return Inertia::render('Patients/Show', [
            'patient' => $patient,
        ]);
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $users = User::all();

        return Inertia::render('Patients/Edit', [
            'patient' => $patient,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:patients,user_id,' . $patient->id,
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient->user_id);

        $patient->delete();
        $user->update(['role' => 'user']); // or 'guest', depending on your role logic

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
