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

}
