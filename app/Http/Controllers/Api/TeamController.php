<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $team['is_lead'] = false;
            $team['is_verified'] = false;
            foreach ($team['users'] as &$team_user) {
                $team_user = [
                    'id' => $team_user['id'],
                    'name' => $team_user['name'],
                    'stu_id' => $team_user['stu_id'],
                    'class' => $team_user['class'],
                    'department' => $team_user['department'],
                    'contact' => $team_user['contact'],
                    'email' => $team_user['email'],
                    'position' => $team_user['pivot']['position'],
                    'status' => $team_user['pivot']['status'],
                ];
                if ($team_user['id'] == $user['id'] && $team_user['position'] == Team::USER_POSITION_LEADER) {
                    $team['is_lead'] = true;
                }
                if ($team_user['id'] == $user['id'] && $team_user['status'] == Team::USER_STATUS_VERIFIED) {
                    $team['is_verified'] = true;
                }
            }
        }
        return $teams;
    }

    /**
     * 创建新的队伍，并以当前用户为队长。
     * 输入：两位队员的学号和与之相匹配的姓名
     *
     * @param Request $request
     *
     * @return array|null|string
     * @throws CustomException
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $request_users = $request->post('users');

        if (count($request_users) > 2) {
            throw new CustomException(__('除队长外，最多 2 名队员，当前提交 :count 名', [
                'count' => count($request_users),
            ]));
        }

        $sync_data = [];
        $errors = [];
        foreach ($request_users as $request_user) {
            if (!$request_user['stu_id']) {
                continue;
            }
            $team_user = User::whereStuId($request_user['stu_id'])->first();
            if (!$team_user) {
                $errors[] = __('学号 :stu_id 不存在或未登录过此网站', [
                    'stu_id' => $request_user['stu_id'],
                ]);
                continue;
            }
            if (isset($team[$team_user->id])) {
                continue;
            }
            if ($team_user->name !== $request_user['name']) {
                $errors[] = __('学号 :stu_id 用户的姓名不是 :name', [
                    'stu_id' => $request_user['stu_id'],
                    'name' => $request_user['name'],
                ]);
                continue;
            }
            $sync_data[$team_user->id] = [
                'position' => Team::USER_POSITION_MEMBER,
                'status' => Team::USER_STATUS_VERIFYING,
            ];
        }
        if ($errors) {
            throw new CustomException('用户信息不匹配', $errors);
        }

        $user = Auth::user();
        $sync_data[$user->id] = [
            'position' => Team::USER_POSITION_LEADER,
            'status' => Team::USER_STATUS_VERIFIED,
        ];

        $team = Team::create([
            'team_id' => '',
        ]);
        $team->users()->sync($sync_data);
        return $team;
    }

    /**
     * 修改自己的队伍中的成员
     *
     * @param Team $team
     *
     * @return Team|Team[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function update(Team $team)
    {
        $user = Auth::user();
        if (!$team->isLeader($user)) {
            throw new CustomException('这支队伍不由您管理');
        }

        $request_users = request()->post('users');
        if (count($request_users) > 2) {
            throw new CustomException(__('最多 2 名队员，当前提交 :user_count 名', [
                'user_count' => count($request_users),
            ]));
        }

        $sync_data = [];
        $errors = [];
        foreach ($request_users as $request_user) {
            if (!$request_user['stu_id']) {
                continue;
            }
            $team_user = User::whereStuId($request_user['stu_id'])->first();
            if (!$team_user) {
                $errors[] = __('学号 :stu_id 不存在或未登录过此网站', [
                    'stu_id' => $request_user['stu_id'],
                ]);
                continue;
            }
            if (isset($team[$team_user->id])) {
                continue;
            }
            if ($team_user->name !== $request_user['name']) {
                $errors[] = __('学号 :stu_id 用户的姓名不是 :name', [
                    'stu_id' => $request_user['stu_id'],
                    'name' => $request_user['name'],
                ]);
                continue;
            }
            $sync_data[$team_user->id] = [
                'position' => Team::USER_POSITION_MEMBER,
                'status' => Team::USER_STATUS_VERIFYING,
            ];
        }
        if ($errors) {
            throw new CustomException('用户信息不匹配', $errors);
        }

        $sync_data[$user->id] = [
            'position' => Team::USER_POSITION_LEADER,
            'status' => Team::USER_STATUS_VERIFIED,
        ];

        $original_users = $team->users->keyBy('id');

        foreach ($sync_data as $key => &$item) {
            if (isset($original_users[$key])) {
                $original_user = $original_users[$key];
                $item['position'] = $original_user->pivot->position;
                $item['status'] = $original_user->pivot->status;
            }
        }

        $team->users()->sync($sync_data);
        return $team;
    }

    /**
     * 同意加入队伍
     *
     * @param Team $team
     *
     * @return Team
     * @throws CustomException
     */
    public function verify(Team $team)
    {
        $user = Auth::user();
        if (!$team->hasUser($user)) {
            throw new CustomException('当前用户不属于这支队伍');
        }
        $team->users()->syncWithoutDetaching([
            $user->id => [
                'status' => Team::USER_STATUS_VERIFIED,
            ],
        ]);
        return $team;
    }

    /**
     * 退出队伍
     *
     * @param Team $team
     *
     * @return Team
     * @throws CustomException
     * @throws \Exception
     */
    public function destory(Team $team)
    {
        $user = Auth::user();
        if (!$team->hasUser($user)) {
            throw new CustomException('当前用户不属于这支队伍');
        }
        $team->users()->detach($user);

        if (!$team->users()->wherePivot('position', Team::USER_POSITION_LEADER)->count()) {
            Log::info("队伍（id = {$team->id}）已没有队长!");
            if (!$team->users()->wherePivot('status', 'verified')->count()) {
                // 没有任何已验证成员，删除队伍
                Log::info("队伍（id = {$team->id}）没有任何已验证成员!");
                $team->users()->sync([]);
                $team->delete();
                Log::info("队伍（id = {$team->id}）已删除!");
            } elseif (!$team->users()->wherePivot('position', Team::USER_POSITION_MEMBER)->count()) {
                // 没有任何成员
                Log::notice("队伍（id = {$team->id}）没有任何成员!");
            } else {
                /** @var User $team_first_user */
                $team_first_user = $team->users()->wherePivot('position', Team::USER_POSITION_MEMBER)->first();
                $team->users()->updateExistingPivot($team_first_user->id, [
                    'position' => Team::USER_POSITION_LEADER,
                ]);
                Log::info("队伍（id = {$team->id}）设置 {$team_first_user->name}（id = {$team_first_user->id}, username = {$team_first_user->username}）为队长！");
            }
        }
        return $team;
    }
}
