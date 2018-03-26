<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Match extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'expired_at',
        'status',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function snapshots()
    {
        return $this->hasMany(MatchSnapshot::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw('status DESC, expired_at DESC, created_at DESC');
    }

    public function makeSnapshot()
    {
        $match = $this;
        $snapshot = null;
        DB::transaction(function () use (&$match, &$snapshot) {
            $snapshot = new MatchSnapshot([
                'title' => $match->title,
                'expired_at' => $match->expired_at,
                'match_created_at' => $match->created_at,
                'match_updated_at' => $match->updated_at,
            ]);
            $match->snapshots()->save($snapshot);
            $match->teams()->chunk(50, function ($teams) use (&$snapshot) {
                $snapshot_users_array = [];
                $created_at = $snapshot->created_at;
                $updated_at = $snapshot->updated_at;
                foreach ($teams as $team) {
                    $team_users = $team->users;
                    foreach ($team_users as $team_user) {
                        $snapshot_users_array[] = [
                            'match_snapshot_id' => $snapshot->id,
                            'user_id' => $team_user->id,
                            'team_id' => $team->id,
                            'team_number' => $team->team_id,
                            'username' => $team_user->username,
                            'stu_id' => $team_user->stu_id,
                            'name' => $team_user->name,
                            'department' => $team_user->department,
                            'major' => $team_user->major,
                            'class' => $team_user->class,
                            'contact' => $team_user->contact,
                            'email' => $team_user->email,
                            'position' => $team_user->pivot->position,
                            'created_at' => $created_at,
                            'updated_at' => $updated_at,
                        ];
                    }
                }
                MatchSnapshotUser::insert($snapshot_users_array);
            });
        });
        return $snapshot;
    }
}
