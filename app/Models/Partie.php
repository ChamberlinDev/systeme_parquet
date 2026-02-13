<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    //
    protected $primaryKey = 'id_partie';
    
    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'qualite'
    ];
}
