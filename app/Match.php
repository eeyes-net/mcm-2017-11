<?php

namespace App;

use App\Libraries\AssignTeamNumber;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Match extends Model
{
    use SoftDeletes;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';

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

    public function scopeOrdered(Builder $query)
    {
        return $query->orderByRaw('status DESC, expired_at DESC, created_at DESC');
    }

    public function getIsAppliedAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        $user = Auth::user();
        return $user->isAppliedMatch($this);
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === self::STATUS_OPEN && Carbon::now() <= $this->expired_at;
    }
}
