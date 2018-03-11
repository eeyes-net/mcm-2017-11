<?php

namespace App\Listeners;

use App\Events\EvilUserInput;
use App\Exceptions\CustomException;

class BanUser
{
    public function handle(EvilUserInput $event)
    {
        $user = auth()->user();
        $user->group = 'banned';
        $user->save();
        auth()->logout();
        throw new CustomException('非法数据');
        // throw new HttpResponseException(RedirectResponse::create(route('logout')));
    }
}
