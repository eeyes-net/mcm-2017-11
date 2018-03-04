<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * 列出当前用户的所有队伍以及成员信息
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams()->with('users')->get()->toArray();
        foreach ($teams as &$team) {
            unset($team['pivot']);
            foreach ($team['users'] as &$user) {
                $user = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'stu_id' => $user['stu_id'],
                    'class' => $user['class'],
                    'department' => $user['department'],
                    'contact' => $user['contact'],
                    'email' => $user['email'],
                    'position' => $user['pivot']['position'],
                ];
            }
        }
        return $teams;
    }

    /**
     * 创建新的队伍，并以当前用户为队长。
     * 输入：两位队员的用户名（NetID）和与之相匹配的姓名
     * @return array|null|string
     * @throws CustomException
     */
    public function store()
    {
        // 验证数据
        $members = request()->post('member');
        if (count($members) > 2) {
            throw new CustomException(__('最多2名队员，当前提交:member_count名', [
                'member_count' => count($members),
            ]));
        }

        $errors = [];
        foreach ($members as $member) {
            $user = User::where('username', $member['net_id'])->first();
            if (!$user) {
                $errors[] = __('NetID :net_id 不存在或未登录过此网站', [
                    'net_id' => $member['net_id'],
                ]);
                continue;
            }
            if ($user->name !== $member['name']) {
                $errors[] = __('NetID :net_id 用户的姓名不是 :name', [
                    'net_id' => $member['net_id'],
                    'name' => $member['name'],
                ]);
            }
        }
        if ($errors) {
            throw new CustomException('用户信息错误', $errors);
        }

        $team = new Team([
            'team_id' => time() . mt_rand(100000, 999999),
        ]);
        $team->save();
        $team->team_id = $team->id;
        $team->save();

        $user = Auth::user();
        $team->users()->save($user, [
            'position' => 'leader',
            'status' => 'verified',
        ]);
        foreach ($members as $member) {
            $user = User::where('username', $member['net_id'])->first();
            $team->users()->save($user, [
                'position' => 'member',
                'status' => 'waiting',
            ]);
        }
    }

    /**
     * 修改自己的队伍中的成员
     * @param int $id 队伍ID
     */
    public function update($id)
    {
        // TODO
    }
}
