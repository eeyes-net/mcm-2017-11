<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\Destroy;
use App\Http\Requests\Team\Store;
use App\Http\Requests\Team\Update;
use App\Http\Requests\Team\Verify;
use App\Http\Resources\Team as TeamResource;
use App\Team;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    /**
     * 列出当前用户的所有队伍以及成员信息
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams()->with(['users' => function (Relation $query) {
            $query->select([
                'users.id',
                'users.stu_id',
                'users.name',
                'users.department',
                'users.class',
                'users.contact',
                'users.email',
            ]);
        }])->get();
        return TeamResource::collection($teams)->additional([
            'meta' => [
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * 创建新的队伍，并以当前用户为队长。
     * 输入：两位队员的学号和与之相匹配的姓名
     *
     * @param \App\Http\Requests\Team\Store $request
     *
     * @return \App\Http\Resources\Team
     * @throws \Throwable
     */
    public function store(Store $request)
    {
        $sync_data = [];

        $user = Auth::user();
        $sync_data[$user->id] = [
            'position' => Team::USER_POSITION_LEADER,
            'status' => Team::USER_STATUS_VERIFIED,
        ];

        $users_id = $request->get('users_id');
        foreach ($users_id as $user_id) {
            $sync_data[$user_id] = [
                'position' => Team::USER_POSITION_MEMBER,
                'status' => Team::USER_STATUS_VERIFYING,
            ];
        }

        DB::transaction(function () use (&$sync_data, &$team) {
            $team = Team::create([
                'team_id' => '',
            ]);
            $team->users()->sync($sync_data);
        });

        return new TeamResource($team);
    }

    /**
     * 修改自己的队伍中的成员
     *
     * @param \App\Http\Requests\Team\Update $request
     * @param \App\Team $team
     *
     * @return \App\Http\Resources\Team
     */
    public function update(Update $request, Team $team)
    {
        $sync_data = [];

        $user = Auth::user();
        $sync_data[$user->id] = [];

        $original_users_id = $team->users()->pluck('users.id')->toArray();
        $users_id = $request->get('users_id');
        foreach ($users_id as $user_id) {
            if (in_array($user_id, $original_users_id)) {
                $sync_data[$user_id] = [];
            } else {
                $sync_data[$user_id] = [
                    'position' => Team::USER_POSITION_MEMBER,
                    'status' => Team::USER_STATUS_VERIFYING,
                ];
            }
        }

        $team->users()->sync($sync_data);
        return new TeamResource($team);
    }

    /**
     * 同意加入队伍
     *
     * @param \App\Http\Requests\Team\Verify $request
     * @param \App\Team $team
     *
     * @return \App\Team
     */
    public function verify(Verify $request, Team $team)
    {
        $user = Auth::user();
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
     * @param \App\Http\Requests\Team\Destroy $request
     * @param \App\Team $team
     *
     * @return \App\Http\Resources\Team
     * @throws \Throwable
     */
    public function destroy(Destroy $request, Team $team)
    {
        $user = Auth::user();
        DB::transaction(function () use (&$team, &$user) {
            $team->users()->detach($user);

            $users = $team->users;
            if ($users->count() <= 0) {
                // 队伍中没有任何人了，删除
                $team->delete();
            } elseif ($users->where('pivot.status', Team::USER_STATUS_VERIFIED)->count() <= 0) {
                // 没有任何已验证成员了，删除
                $team->users()->sync([]);
                $team->delete();

            } elseif ($users->where('pivot.position', Team::USER_POSITION_LEADER)->count() <= 0) {
                // 有已验证成员，但是没有队长了，分配一个队长
                $team_user = $users->where('pivot.position', Team::USER_POSITION_MEMBER)->first();
                if (!$team_user) {
                    Log::error("队伍（id = {$team->id}）有成员但是既没有队长也没有队员！");
                } else {
                    $team->users()->updateExistingPivot($team_user->id, [
                        'position' => Team::USER_POSITION_LEADER,
                    ]);
                    Log::info("{$user->name}（id = {$user->id}, username = {$user->username}）退出队伍（id = {$team->id}），自动分配 {$team_user->name}（id = {$team_user->id}, username = {$team_user->username}）为队长！");
                }
            }
        });
        return new TeamResource($team);
    }
}
