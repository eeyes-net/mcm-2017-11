<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    const GROUP_ADMIN = 'admin';
    const GROUP_STUDENT = 'student';

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
        'group',
        'experience',
        'coach_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'matches_id',
        'leading_teams_id',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot(['position', 'status'])->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->group === self::GROUP_ADMIN;
    }

    /**
     * 获取当前用户为队长的所有队伍的ID。（有缓存）
     *
     * @return array [比赛ID => 参赛的队伍ID]
     */
    public function getLeadingTeamsIdAttribute($value)
    {
        if (is_null($value)) {
            $value = $this->teams()->wherePivot('position', Team::USER_POSITION_LEADER)->pluck('teams.id')->toArray();
            $this->leading_teams_id = $value;
        }
        return $value;
    }

    /**
     * 获取当前用户已报名的所有比赛ID。（有缓存）
     *
     * @param array $value
     *
     * @return array [比赛ID => 参赛的队伍ID]
     */
    public function getMatchesIdAttribute($value)
    {
        if (is_null($value)) {
            // TODO optimise SQL query
            $user_teams_id = $this->teams()->pluck('teams.id')->toArray();
            $value = DB::table('match_team')->select(['match_id', 'team_id'])->whereIn('team_id', $user_teams_id)->pluck('team_id', 'match_id')->toArray();
            $this->matches_id = $value;
        }
        return $value;
    }

    /**
     * 判断当前用户是否已报名某比赛
     *
     * @param int|Match $match_id
     *
     * @return bool
     */
    public function isAppliedMatch($match_id)
    {
        if ($match_id instanceof Match) {
            $match_id = $match_id->id;
        }
        return isset($this->matches_id[$match_id]);
    }
}
