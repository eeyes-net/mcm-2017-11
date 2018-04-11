<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Update;
use App\Http\Resources\User as UserResource;
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
     * @param \App\Http\Requests\User\Update $request
     *
     * @return \App\Http\Resources\User
     */
    public function update(Update $request)
    {
        $user = Auth::user();
        $user->update($request->only([
            'contact',
            'email',
            'experience',
            'coach_name',
        ]));
        return new UserResource($user);
    }
}
