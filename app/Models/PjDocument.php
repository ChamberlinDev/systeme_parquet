<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjDocument extends Model
{
    protected $table = 'pj_documents';

    protected $fillable = [
        'id_dossier',
        'id_instruction',
        'uploaded_by',
        'type_document',
        'description',
        'file_path',
        'original_name',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    public function instruction()
    {
        return $this->belongsTo(Instruction::class, 'id_instruction');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public static array $typeLabels = [
        'pv'        => 'Procès-verbal',
        'photo'     => 'Photo',
        'video'     => 'Vidéo',
        'expertise' => 'Expertise',
        'autre'     => 'Autre',
    ];

    public static array $typeIcons = [
        'pv'        => 'fa-file-alt',
        'photo'     => 'fa-image',
        'video'     => 'fa-video',
        'expertise' => 'fa-microscope',
        'autre'     => 'fa-file',
    ];
}
