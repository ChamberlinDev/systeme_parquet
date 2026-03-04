<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parquet extends Model
{
    //
    protected $fillable = [
        'nom',
        'ville',
        'adresse',
        'telephone',
        'email',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'parquet_id');
    }
}
