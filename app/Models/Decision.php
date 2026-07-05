<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    //
    protected $primaryKey = 'id_decision';

    protected $fillable = [
        'id_audience',
        'id_dossier',
        'type_decision',
        'contenu',
        'date_decision',
        'signatures',
    ];

    public function audience()
    {
        return $this->belongsTo(Audience::class, 'id_audience', 'id_audience');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    public function executions()
    {
        return $this->hasMany(Execution::class, 'id_decision');
    }
}
