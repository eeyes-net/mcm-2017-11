<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    const USER_POSITION_LEADER = 'leader';
    const USER_POSITION_MEMBER = 'member';
    const USER_STATUS_VERIFIED = 'verified';
    const USER_STATUS_VERIFYING = 'verifying';

    protected $fillable = [
        'team_id',
        'leader_user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['position', 'status'])->withTimestamps();
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->withTimestamps();
    }

    public function recruits()
    {
        return $this->hasMany(Recruit::class);
    }

    /**
     * @param User|int $user
     *
     * @return bool
     */
    public function isLeader($user)
    {
        if ($user instanceof User) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $leader = $this->users()->wherePivot('position', Team::USER_POSITION_LEADER)->first();
        return $leader->id === $user_id;
    }

    /**
     * @param User|int $user
     *
     * @return bool
     */
    public function hasUser($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        return $this->users()->where('user_id', $user)->exists();
    }
}
