<?php

namespace App\Exceptions;

use Exception;

class EvilInputException extends Exception
{
    public function __construct($message = "")
    {
        self::banUser();
        parent::__construct($message);
    }

    public static function banUser()
    {
        $user = auth()->user();
        $user->group = 'banned';
        $user->save();
        auth()->logout();
        session()->flush();
        session()->save();
    }
}
