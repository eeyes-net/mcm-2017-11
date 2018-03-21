<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchSnapshot extends Model
{
    protected $fillable = [
        'title',
        'expired_at',
        'match_created_at',
        'match_updated_at',
    ];

    public function users()
    {
        return $this->hasMany(MatchSnapshotUser::class);
    }
}
