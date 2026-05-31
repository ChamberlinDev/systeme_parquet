<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    //
    protected $primaryKey = 'id_execution';

    protected $fillable = [
        'id_decision',
        'id_institution',
        'type_peine',
        'date_execution',
        'statut_execution',
    ];

    public function decision()
    {
        return $this->belongsTo(Decision::class, 'id_decision');
    }
    public function institution()
    {
        return $this->belongsTo(Institution_executante::class, 'id_institution');
    }
}
