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
}
