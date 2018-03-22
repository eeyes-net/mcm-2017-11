<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class EvilUserInput
{
    use Dispatchable;

    public function __construct($message = '')
    {
        $this->message = $message;
    }
}
