<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'stu_id',
        'name',
        'department',
        'major',
        'class',
        'contact',
        'email',
        'password',
        'group',
        'experience',
        'coach_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot(['position', 'status'])->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->group === 'admin';
    }

    public function getAppliedMatchesIdAttribute() {
        $matches_id = [];
        /** @var Team $team */
        foreach($this->teams as $team) {
            // TODO optimise SQL query
            $matches_id = array_merge($matches_id, $team->matches->pluck('id')->toArray());
        }
        return array_values(array_unique($matches_id));
    }
}
