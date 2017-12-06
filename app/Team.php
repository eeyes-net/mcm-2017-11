<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'team_id',
        'leader_user_id',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->withTimestamps();
    }

    public function recruits()
    {
        return $this->hasMany(Recruit::class);
    }
}
