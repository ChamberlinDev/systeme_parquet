<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution_executante extends Model
{
    //
    protected $fillable = [
        'id_institution',
        'nom',
        'type_institution'
    ];

     public function executions()
    {
        return $this->hasMany(Execution::class, 'id_institution');
    }
}
