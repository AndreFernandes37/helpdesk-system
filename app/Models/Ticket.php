<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'categoria_id',
        'user_id',
        'status',
    ];

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    
}
