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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }
}
