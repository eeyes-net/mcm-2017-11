<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruit extends Model
{
    protected $fillable = [
        'team_id',
        'tags',
        'members',
        'description',
        'contact',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
