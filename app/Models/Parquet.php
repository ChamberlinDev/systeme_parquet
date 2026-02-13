<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parquet extends Model
{
    //
    protected $fillable = [
        'id_parquet',
        'nom_masgistrat',
        'fonction',
        'decision_orientation',
        'date_decision'
    ];
}
