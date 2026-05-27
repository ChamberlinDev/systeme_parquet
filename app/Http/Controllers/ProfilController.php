<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    //
  public function profil_view()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return view('admin.profil.voir', compact('user'));
        }

        if ($user->hasRole('greffier')) {
            return view('greffier.profil.voir', compact('user'));
        }

        if ($user->hasRole('juge')) {
            return view('juge.profil.voir', compact('user'));
        }

        if ($user->hasRole('procureur')) {
            return view('procureur.profil.voir', compact('user'));
        }

        abort(403, 'Rôle non autorisé');
    }
   
}
