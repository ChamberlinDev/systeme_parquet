<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution_executante extends Model
{
    //
    protected $primaryKey = 'id_institution';

    protected $fillable = [
        'nom',
        'email',
        'type_institution',
    ];

     public function executions()
    {
        return $this->hasMany(Execution::class, 'id_institution');
    }
}
