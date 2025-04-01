<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resposta extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
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
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
