<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\Eeyes\Api\Permission;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use phpCAS;

class CasLoginController extends LoginController
{
    protected $redirectTo = '/';

    public function __construct()
    {
        phpCAS::client(CAS_VERSION_2_0, config('cas.host'), config('cas.port'), config('cas.context'));
        phpCAS::setNoCasServerValidation();
    }

    public function login()
    {
        phpCAS::forceAuthentication();
        $net_id = phpCAS::getUser();
        $user = User::where('username', $net_id)->first();
        if (!$user) {
            $user = new User([
                'username' => $net_id,
                'stu_id' => '',
                'name' => '',
                'department' => '',
                'major' => '',
                'class' => '',
                'contact' => '',
                'email' => $net_id . '@xjtu.edu.cn',
                'password' => '*',
                'group' => 'student', // default as student
            ]);
            $user->save();
        }
        Auth::login($user);
        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        session_unset();
        session_destroy();
        return redirect(phpCAS::getServerLogoutURL() . '?' . http_build_query([
                'service' => url('/'),
            ]));
    }
}
