<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    //
    protected $fillable = [
        'id_decision',
        'type_decision',
        'contenu',
        'date_decision',
        'sagnatures'
    ];

    public function audience()
    {
        return $this->belongsTo(Audience::class, 'id_audience');
    }
    
    public function executions()
    {
        return $this->hasMany(Execution::class, 'id_decision');
    }
}
