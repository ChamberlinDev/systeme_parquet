<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    //

    public function registerForm()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        return view('auth.register', compact('roles'));
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name'     => 'required|string',
    //         'email'    => 'required|email|unique:users',
    //         'password' => 'required|min:6',
    //         'role'     => 'required|string|exists:roles,name',
    //     ]);

    //     // Création utilisateur
    //     $user = User::create([
    //         'name'     => $request->name,
    //         'email'    => $request->email,
    //         'password' => Hash::make($request->password),
    //         'is_actif'=>false,
    //     ]);

    //     // Assignation du rôle Spatie
    //     $user->assignRole($request->role);

    //     return redirect()->back()->with('success', "Utilisateur créé avec succès !");
    // }
}
