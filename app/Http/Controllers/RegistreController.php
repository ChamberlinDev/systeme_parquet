<?php

namespace App\Http\Controllers;

use App\Models\Registre;
use Illuminate\Http\Request;

class RegistreController extends Controller
{
    //
    public function index()
    {
        $registres = Registre::all();
        return view('greffier.registres.index', compact('registres'));
    }
    public function create()
    {
        return view('greffier.registres.ajout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'code' => 'required|string|unique:registres,code',
        ]);

        Registre::create($request->only('nom', 'code'));

        return redirect()->route('registres.index')->with('success', 'Registre ajouté avec succès');
    }

    public function edit(Registre $registre)
    {
        return view('greffier.registres.edit', compact('registre'));
    }

    public function update(Request $request, Registre $registre)
    {
        $request->validate([
            'nom' => 'required|string',
            'code' => 'required|string|unique:registres,code,' . $registre->id,
        ]);

        $registre->update($request->only('nom', 'code'));

        return redirect()->route('greffier.registres.index')->with('success', 'Registre mis à jour');
    }

    public function destroy(Registre $registre)
    {
        $registre->delete();
        return redirect()->route('greffier.registres.index')->with('success', 'Registre supprimé');
    }
}
