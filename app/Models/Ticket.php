<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public $incrementing = false;
    protected $keyType = 'string';

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    
}
