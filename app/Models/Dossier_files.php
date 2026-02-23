<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier_files extends Model
{
    //

    protected $fillable = ['file_path'];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
