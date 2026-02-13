<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function create_user_form()
    {
        $roles = Role::all();
        return view('admin.users.ajout', compact('roles'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string|exists:roles,name',
            'is_actif' => 'required|boolean',
        ]);

        // Création utilisateur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_actif' => $request->is_actif,
        ]);

        // Assignation du rôle Spatie
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', "Utilisateur créé avec succès !");
    }


    public function activer($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_actif' => 1
        ]);

        return back()->with('success', 'Utilisateur activé avec succès.');
    }

    public function desactiver($id){
        $user = User::findOrFail($id);
           // Sécurité : empêcher de se désactiver soi-même
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update([
            'is_actif' => 0
        ]);

        return back()->with('success', 'Utilisateur désactivé avec succès.');
    }
}
