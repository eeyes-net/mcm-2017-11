<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return User::paginate();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function team(User $user)
    {
        return $user->teams()->get();
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->only([
            'stu_id',
            'name',
            'department',
            'major',
            'class',
            'contact',
            'email',
        ]));
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $user;
    }
}
