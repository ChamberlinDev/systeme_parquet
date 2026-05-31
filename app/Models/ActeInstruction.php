<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActeInstruction extends Model
{
    protected $table = 'actes_instruction';

    protected $fillable = [
        'id_instruction',
        'id_demandeur',
        'type_acte',
        'objet',
        'date_demande',
        'date_echeance',
        'statut',
        'date_execution',
        'resultat',
    ];

    protected $casts = [
        'date_demande'   => 'date',
        'date_echeance'  => 'date',
        'date_execution' => 'date',
    ];

    public function instruction()
    {
        return $this->belongsTo(Instruction::class, 'id_instruction');
    }

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'id_demandeur');
    }

    public function isEnRetard(): bool
    {
        return $this->date_echeance
            && $this->date_echeance->isPast()
            && !in_array($this->statut, ['execute', 'annule']);
    }
}
