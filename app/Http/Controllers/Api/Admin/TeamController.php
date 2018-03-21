<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        return Team::with('users')->orderBy('id', 'desc')->paginate();
    }

    public function show(Team $team)
    {
        return $team->load('users');
    }

    public function match(Team $team)
    {
        return $team->matches()->get();
    }

    public function update(Team $team)
    {
        // TODO
        throw new \Exception('TODO');
    }
}
