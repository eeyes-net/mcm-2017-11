<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 获取当前用户信息
     *
     * @return \App\Http\Resources\User
     */
    public function show()
    {
        return new UserResource(Auth::user());
    }

    /**
     * 修改当前用户信息
     *
     * @param Request $request
     *
     * @return \App\Http\Resources\User
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->validate([
            'contact' => 'numeric',
            'email' => 'email',
            'experience' => 'string|max:4096',
            'coach_name' => 'string|max:255',
        ]));
        return new UserResource($user);
    }
}
