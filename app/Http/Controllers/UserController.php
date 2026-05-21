<?php

namespace App\Http\Controllers;

use App\Models\Parquet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Afficher la liste des utilisateurs
    public function index()
    {
        $user  = Auth::user();
        $query = User::with(['parquet', 'roles']);

        if ($user->hasRole('admin') && is_null($user->parquet_id)) {
            // Admin global (pas de parquet) → voit tout
            $users = $query->get();
        } else {
            // Admin de parquet → voit uniquement son parquet
            $users = $query
                ->where('parquet_id', $user->parquet_id)
                ->get();
        }

        return view('admin.users.index', compact('users'));
    }

    // Afficher le formulaire de création d'utilisateur
    public function create_user_form()
    {
        $users = User::all();
        $roles = Role::all();
        $parquets = Parquet::all();

        return view('admin.users.ajout', compact('roles', 'parquets', 'users'));
    }

    // Traiter la création d'utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string|exists:roles,name',
            'is_actif' => 'required|boolean',
            'parquet_id' => 'required|exists:parquets,id',
        ]);

        // Création utilisateur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_actif' => $request->is_actif,
            'parquet_id' => $request->parquet_id,

        ]);

        // Assignation du rôle 
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', "Utilisateur créé avec succès !");
    }

    public function details($id)
    {
        $user = User::with('parquet')->findOrFail($id);
        return view('admin.users.detail', compact('user'));
    }



    // Activer un utilisateur
    public function activer($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_actif' => 1
        ]);

        return back()->with('success', 'Utilisateur activé avec succès.');
    }

    // Désactiver un utilisateur
    public function desactiver($id)
    {
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

    // Donner les permissions à un utilisateur

}
