<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    //

    public function accueil_admin()
    {
        $users = User::with('roles')->get();
        $dossiers = Dossier::with(['registre', 'parties', 'files'])->latest()->paginate(10);
        return view('welcome', compact('users', 'dossiers'));
    }

    public function accueil_greffier()
    {
        $user = Auth::user();
        $dossiers = Dossier::with(['registre', 'parties', 'files'])
            ->where('parquet_id', $user->parquet_id)
            ->where('id_greffier', $user->id)
            ->latest()
            ->paginate(10);
        $dossier = Dossier::where('id_greffier', Auth::id())->get(); 
        return view('gref_accueil', compact('dossier', 'dossiers'));
    }


    public function accueil_procureur()
    {
        return view('procureur.accueil');
    }

    public function accueil_juge()
    {
        return view('juge.accueil');
    }

   
    // Dossiers 

     public function show($id)
    {
        $dossier = Dossier::with(['registre', 'parties', 'files', 'parquet', 'procureur'])
            ->findOrFail($id);

        $procureurs = User::role('procureur')
            ->where('parquet_id', Auth::user()->parquet_id)
            ->get();

        return view('admin.dossiers.details', compact('dossier', 'procureurs'));
    }
}
