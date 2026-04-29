<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    //

    public function accueil_admin()
    {
        $users = User::with('roles')->get();
        return view('welcome', compact('users'));
    }

    public function accueil_greffier()
    {

        return view('gref_accueil');
    }

   
}
