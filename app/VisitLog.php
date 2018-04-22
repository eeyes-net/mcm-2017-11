<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    protected $fillable = [
        'path',
        'method',
        'ip',
        'user_agent',
        'query',
        'body',
        'response_code',
        'response_length',
        'response_body',
    ];
}
