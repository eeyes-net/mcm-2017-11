<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Recruit;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitController extends Controller
{
    public function index()
    {
        return Recruit::latest()->paginate();
    }

    public function show(Recruit $recruit)
    {
        return $recruit;
    }

    public function update(Request $request, Recruit $recruit)
    {
        $recruit->update($request->only([
            'tags',
            'members',
            'description',
            'contact',
        ]));
        return $recruit;
    }

    public function destroy(Recruit $recruit)
    {
        $recruit->delete();
        return $recruit;
    }
}
