<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        // TODO: Logic to show user by ID
    }

    public function edit($id)
    {
        // TODO: Logic to edit user by ID
    }

    public function update(Request $request)
    {
        // TODO: Logic to update user
    }
}
