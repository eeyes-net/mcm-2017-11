<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'title',
        'expired_at',
        'status',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw('status DESC, expired_at DESC, created_at DESC');
    }
}
