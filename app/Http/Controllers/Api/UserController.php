<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 获取当前用户信息
     */
    public function show()
    {
        return Auth::user();
    }

    /**
     * 修改当前用户信息
     * @param Request $request
     * @return \App\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->validate([
            'contact' => 'numeric',
            'email' => 'email',
        ]));
        return $user;
    }
}
