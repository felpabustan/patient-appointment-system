<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }
}
