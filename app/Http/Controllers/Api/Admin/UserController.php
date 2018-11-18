<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = new User();
        $q = $request->get('q');
        if ($q) {
            $user = $user->search($q);
        }
        return $user->latest()->paginate();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function team(User $user)
    {
        return $user->teams()->with('users')->get();
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
            'group',
            'experience',
            'coach_name',
        ]));
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $user;
    }
}
