<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    public function getPlainTextAttribute()
    {
        return preg_replace('/\\s+/', ' ', strip_tags($this->content));
    }
}
