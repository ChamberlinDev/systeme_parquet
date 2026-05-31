<?php

namespace App\Http\Controllers;

use App\Models\Instruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageInstructionController extends Controller
{
    public function store(Request $request, Instruction $instruction)
    {
        $request->validate([
            'objet'   => 'required|string|max:150',
            'contenu' => 'required|string|min:5',
        ]);

        $instruction->messages()->create([
            'expediteur_id' => Auth::id(),
            'objet'         => $request->objet,
            'contenu'       => $request->contenu,
            'lu'            => false,
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}
