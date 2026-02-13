<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    //
    protected $fillable = [
        'id_audience',
        'date_audience',
        'salle',
        'type_audience',
        'role'
    ];
}
