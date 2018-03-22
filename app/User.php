<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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

    /**
     * 获取当前用户为队长的所有队伍
     *
     * @return array
     */
    public function getLeadingTeamsIdAttribute() {
        return $this->teams()->wherePivot('position', Team::USER_POSITION_LEADER)->pluck('teams.id')->toArray();
    }

    /**
     *  获取当前用户已报名的所有比赛ID，以 [比赛ID => 参赛的队伍ID] 的形式返回
     *
     * @return array Array like [match_id => team_id]
     */
    public function getMatchesIdAttribute()
    {
        // TODO optimise SQL query
        $user_teams_id = $this->teams()->pluck('teams.id')->toArray();
        $matches_id = DB::table('match_team')->select(['match_id', 'team_id'])->whereIn('team_id', $user_teams_id)->pluck('team_id', 'match_id')->toArray();
        return $matches_id;
    }
}
