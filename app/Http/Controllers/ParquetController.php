<?php

namespace App\Http\Controllers;

use App\Models\Parquet;
use Illuminate\Http\Request;

class ParquetController extends Controller
{
    // 

    public function index(){

        $parquets=Parquet::all();
        return view('admin.parquet.index', compact('parquets'));
    }


    public function create()
    {
        return view('admin.parquet.ajout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);
        Parquet::create($request->all());
        return redirect()->route('parquets.index')->with('success', 'Parquet créé avec succès.');
    }

    public function edit($id){
    
    $parquet= Parquet::findOrfail($id);
    return view('admin.parquet.edit', compact('parquet'));
    
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);
        $parquet = Parquet::findOrFail($id);
        $parquet->update($request->all());
        return redirect()->route('parquets.index')->with('success', 'Parquet mis à jour avec succès.');
    }

    public function destroy($id){
        $parquet= Parquet::findOrfail($id);
        $parquet->delete();
        return redirect()->route('parquets.index')->with('success', 'Parquet supprimé avec succès.');
    }
}
