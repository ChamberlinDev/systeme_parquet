<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageInstruction extends Model
{
    protected $table = 'messages_instruction';

    protected $fillable = [
        'id_instruction',
        'expediteur_id',
        'objet',
        'contenu',
        'lu',
    ];

    public function instruction()
    {
        return $this->belongsTo(Instruction::class, 'id_instruction');
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
