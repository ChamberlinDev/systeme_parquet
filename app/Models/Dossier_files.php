<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier_files extends Model
{
    //

    protected $fillable = ['file_path', 'id_dossier'];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }
}
