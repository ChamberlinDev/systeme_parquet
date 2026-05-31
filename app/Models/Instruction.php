<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = [
        'id_dossier',
        'id_juge',
        'id_procureur',
        'date_saisine',
        'statut',
        'date_cloture',
        'motif_cloture',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    public function juge()
    {
        return $this->belongsTo(User::class, 'id_juge');
    }

    public function procureur()
    {
        return $this->belongsTo(User::class, 'id_procureur');
    }

    public function actes()
    {
        return $this->hasMany(ActeInstruction::class, 'id_instruction')->latest();
    }

    public function detentions()
    {
        return $this->hasMany(MesureDetention::class, 'id_instruction')->latest();
    }

    public function messages()
    {
        return $this->hasMany(MessageInstruction::class, 'id_instruction')->latest();
    }

    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }
}
