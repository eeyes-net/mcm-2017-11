<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Match;

class MatchController extends Controller
{
    public function index()
    {
        return view('index.match.index', [
            'matches' => Match::orderByRaw('status DESC, expired_at DESC, created_at DESC')->paginate(12),
        ]);
    }

    public function show($id)
    {
        return view('index.match.show', [
            'match' => Match::find($id),
        ]);
    }

    public function apply($id)
    {
        /** @var \App\Match $match */
        $match = Match::find($id);
        if (!$match || $match->status !== 'open') {
            return [
                'code' => '400',
                'msg' => __('ID为:id的比赛不存在，或此比赛目前禁止报名', [
                    'id' => $id,
                ]),
            ];
        }
    }

    public function submit($id)
    {
        /** @var \App\Match $match */
        $match = Match::find($id);
        if (!$match || $match->status !== 'open') {
            return [
                'code' => '400',
                'msg' => __('ID为:id的比赛不存在，或此比赛目前禁止报名', [
                    'id' => $id,
                ]),
            ];
        }
        /** @var \App\User $user */
        $user = auth()->user();
        // if (!$user) { //TODO: ONLY FOR TEST
        //     $user = User::first();
        // }
        $team_id = request()->post('team_id');
        $can_insert = $user->teams()// 当前登录用户的所有的队伍
        ->where('teams.id', $team_id)// 找到对应队伍
        ->wherePivot('position', 'leader')// 必须是队长
        // ->wherePivot('status', 'verified') // 必须是验证之后的，通常队长自己创建的队伍，自己一定是队长
        ->limit(1)
            ->count();
        if (!$can_insert) {
            return [
                'code' => '400',
                'msg' => __('编号为:team_id的队伍不存在，或此队伍不由您管理', [
                    'team_id' => $team_id,
                ]),
            ];
        }
        $match->teams()->attach($team_id);
    }
}
