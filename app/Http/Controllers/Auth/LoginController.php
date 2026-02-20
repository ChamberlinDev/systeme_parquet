<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function loginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ]);
        }

        $user = Auth::user();

        // Compte désactivé
        if (!$user->is_actif) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte est désactivé.',
            ]);
        }

        // FORCER LE CHANGEMENT DE MOT DE PASSE
        if ($user->must_change_password) {
            return redirect()->route('change_password.form');
        }

        // Redirection normale par rôle
        if ($user->hasRole('admin')) return redirect('/accueil_admin');
        if ($user->hasRole('greffier')) return redirect('/accueil_greffier');


        Auth::logout();
        return back()->withErrors([
            'email' => 'Aucun rôle valide n’est associé à ce compte.',
        ]);
    }


    public function change_password_form()
    {
        return view('auth.change_pass');
    }
    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->must_change_password = false;
        $user->save();

        // Redirection après changement
        if ($user->hasRole('admin')) return redirect('/accueil_admin');
        if ($user->hasRole('greffier')) return redirect('/accueil_greffier');


        return redirect('/');
    }

    public function logout(Request $request)
    {
        // Déconnexion de l'utilisateur
        Auth::logout();
        // Invalider la session
        $request->session()->invalidate();

        // Régénérer le token CSRF
        $request->session()->regenerateToken();

        // Redirection vers la page de login
        return redirect('/')->with('success', 'Déconnexion réussie');
    }
}
