<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function logout()
    {
        Auth::logout();
        Session::flush();
        Session::save();
        session_start();
        session_destroy();
        return redirect(config('eeyes.account.url') . '/logout?url=' . urlencode(url('/')));
    }
}
