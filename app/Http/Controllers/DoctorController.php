<?php

namespace App\Http\Controllers;
use App\Models\DoctorProfile as Doctor;
use Inertia\Inertia;
use App\Models\User; 

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->paginate(20);  
    
        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')->get();
        return Inertia::render('Doctors/Create', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctor_profiles,user_id',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }
    
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id); 
        
        return Inertia::render('Doctors/Edit', [
            'doctor' => $doctor,
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctor_profiles,user_id,' . $doctor->id,
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
    
        $doctor->update($validated);
    
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }
}
