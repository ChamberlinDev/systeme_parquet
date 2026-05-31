<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MesureDetention extends Model
{
    protected $table = 'mesures_detention';

    protected $fillable = [
        'id_instruction',
        'date_debut',
        'date_echeance',
        'statut',
        'motif',
    ];

    protected $casts = [
        'date_debut'    => 'date',
        'date_echeance' => 'date',
    ];

    public function instruction()
    {
        return $this->belongsTo(Instruction::class, 'id_instruction');
    }

    public function joursRestants(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->date_echeance, false);
    }

    public function alerteNiveau(): string
    {
        if ($this->statut === 'levee') return 'none';
        $jours = $this->joursRestants();
        if ($jours < 0)  return 'danger';   // dépassé
        if ($jours <= 7) return 'warning';  // seuil d'alerte
        return 'success';
    }
}
