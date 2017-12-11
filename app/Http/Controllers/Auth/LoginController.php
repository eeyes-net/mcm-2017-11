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
        session_unset();
        session_destroy();
        return redirect('/');
    }
}
