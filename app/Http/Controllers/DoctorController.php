<?php

namespace App\Http\Controllers;
use App\Models\DoctorProfile as Doctor;
use Inertia\Inertia;

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

    public function show($id)
    {
        // TODO: Logic to retrieve doctor by ID
    }

    public function create()
    {
        // TODO: Logic to show form for creating a new doctor
    }

    public function store(Request $request)
    {
        // TODO: Logic to store a new doctor
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic to update doctor by ID
    }
}
