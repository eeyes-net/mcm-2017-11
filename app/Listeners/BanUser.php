<?php

namespace App\Listeners;

use App\Events\EvilUserInput;

class BanUser
{
    public function handle(EvilUserInput $event)
    {
        $user = auth()->user();
        $user->group = 'banned';
        $user->save();
        auth()->logout();
        session()->flush();
        session()->save();
        session_destroy();
        abort(422, '非法数据');
        // throw new HttpResponseException(RedirectResponse::create(route('logout')));
    }
}
