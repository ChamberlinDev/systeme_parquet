<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\DossierHistorique;
use App\Models\Instruction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructionController extends Controller
{
    public function indexJuge()
    {
        $instructions = Instruction::with(['dossier.registre', 'procureur'])
            ->where('id_juge', Auth::id())
            ->latest()
            ->paginate(10);

        $alertes = Instruction::with('detentions')
            ->where('id_juge', Auth::id())
            ->where('statut', 'en_cours')
            ->get()
            ->flatMap(fn($i) => $i->detentions->where('statut', '!=', 'levee'))
            ->filter(fn($d) => $d->joursRestants() <= 7)
            ->count();

        return view('juge.instruction.index', compact('instructions', 'alertes'));
    }

    public function indexProcureur()
    {
        $instructions = Instruction::with(['dossier.registre', 'juge'])
            ->where('id_procureur', Auth::id())
            ->latest()
            ->paginate(10);

        return view('procureur.instruction.index', compact('instructions'));
    }

    public function create(Dossier $dossier)
    {
        abort_unless(
            $dossier->decision_orientation === 'requisitoire_introductif',
            403,
            'Seuls les dossiers avec réquisitoire introductif peuvent ouvrir une instruction.'
        );

        abort_if(
            $dossier->instructions()->where('statut', 'en_cours')->exists(),
            403,
            'Une instruction est déjà en cours pour ce dossier.'
        );

        $juges = User::role('juge')->get();

        return view('procureur.instruction.ouvrir', compact('dossier', 'juges'));
    }

    public function store(Request $request, Dossier $dossier)
    {
        $request->validate([
            'id_juge'      => 'required|exists:users,id',
            'date_saisine' => 'required|date',
        ]);

        $instruction = Instruction::create([
            'id_dossier'   => $dossier->id_dossier,
            'id_juge'      => $request->id_juge,
            'id_procureur' => Auth::id(),
            'date_saisine' => $request->date_saisine,
            'statut'       => 'en_cours',
        ]);

        DossierHistorique::create([
            'id_dossier' => $dossier->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Instruction ouverte',
            'detail'     => 'Saisine du juge d\'instruction le ' . \Carbon\Carbon::parse($request->date_saisine)->format('d/m/Y'),
        ]);

        return redirect()->route('instructions.show', $instruction)
            ->with('success', 'Instruction ouverte et juge saisi.');
    }

    public function show(Instruction $instruction)
    {
        $instruction->load([
            'dossier.registre',
            'dossier.parties',
            'juge',
            'procureur',
            'actes.demandeur',
            'detentions',
            'messages.expediteur',
        ]);

        // Marquer messages non lus comme lus pour l'utilisateur courant
        $instruction->messages()
            ->where('expediteur_id', '!=', Auth::id())
            ->where('lu', false)
            ->update(['lu' => true]);

        $juges = User::role('juge')->get();

        return view('instructions.show', compact('instruction', 'juges'));
    }

    public function clore(Request $request, Instruction $instruction)
    {
        abort_unless($instruction->isEnCours(), 403, 'Cette instruction est déjà clôturée.');

        $request->validate([
            'statut'        => 'required|in:clos_renvoi,clos_non_lieu',
            'motif_cloture' => 'required|string|min:10',
            'date_cloture'  => 'required|date',
        ]);

        $instruction->update([
            'statut'        => $request->statut,
            'motif_cloture' => $request->motif_cloture,
            'date_cloture'  => $request->date_cloture,
        ]);

        $nouveauStatut = $request->statut === 'clos_renvoi' ? 'Orienté' : 'Classé';
        $instruction->dossier->update(['statut' => $nouveauStatut]);

        $labels = [
            'clos_renvoi'    => 'Clôture avec renvoi en audience',
            'clos_non_lieu'  => 'Clôture par ordonnance de non-lieu',
        ];

        DossierHistorique::create([
            'id_dossier' => $instruction->id_dossier,
            'user_id'    => Auth::id(),
            'action'     => 'Instruction clôturée',
            'detail'     => $labels[$request->statut] . ' — ' . $request->motif_cloture,
        ]);

        return redirect()->route('instructions.show', $instruction)
            ->with('success', 'Instruction clôturée. Dossier → ' . $nouveauStatut . '.');
    }
}
