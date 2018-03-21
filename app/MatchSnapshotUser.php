<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchSnapshotUser extends Model
{
    protected $fillable = [
        'match_snapshot_id',
        'user_id',
        'team_id',
        'team_number',
        'username',
        'stu_id',
        'name',
        'department',
        'major',
        'class',
        'contact',
        'email',
        'position',
    ];

    public function matchSnapshot()
    {
        return $this->belongsTo(MatchSnapshot::class);
    }
}
