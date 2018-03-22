<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Exceptions\EvilInputException;
use App\Http\Controllers\Controller;
use App\Recruit;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitController extends Controller
{
    /**
     * 所有招募信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $tags = $request->get('tags');
        $tags = explode(',', $tags);
        $query = new Recruit();
        foreach ($tags as $tag) {
            $query = $query->orWhere('tags', 'like', "%{$tag}%");
        }
        $recruits = $query->latest()->paginate(12);
        return $recruits;
    }

    /**
     * 当前用户管理的队伍的招募信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function currentUser()
    {
        $user = Auth::user();
        $team_ids = $user->teams()->wherePivot('position', 'leader')->pluck('teams.id');
        $recruits = Recruit::whereIn('team_id', $team_ids)->get();
        return $recruits;
    }

    /**
     * 获取所有标签
     *
     * @return array
     */
    public function tags()
    {
        return config('mcm.recruit_tags', []);
    }

    /**
     * 发布招募信息
     *
     * @param Request $request
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws CustomException
     * @throws EvilInputException
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'team_id' => 'required|numeric',
        ]);
        $user = Auth::user();
        $team = Team::find($data['team_id']);
        if (!$team->isLeader($user->id)) {
            throw new CustomException('这支队伍不由您来管理');
        }

        // 检查用户数量
        $user_count = $team->users()->count();
        $user_limit = config('mcm.team_user_limit', 3);
        if ($user_count === $user_limit) {
            throw new CustomException(__('当前队伍已达到人数上限 :limit 人，不能发布招募', [
                'limit' => $user_limit,
            ]));
        } elseif ($user_count > $user_limit) {
            throw new EvilInputException(__('当前队伍（ID = :team_id）人数 :count 人，超过人数上限（:limit 人）', [
                'team_id' => $team->id,
                'count' => $user_count,
                'limit' => $user_limit,
            ]));
        }

        $data = $request->validate([
            'tags' => 'required',
            'description' => 'required',
            'contact' => 'required',
        ]);
        if (!is_array($data['tags'])) {
            $data['tags'] = explode(',', $data['tags']);
        }

        // 去除非法标签
        $tags_available = config('mcm.recruit_tags');
        $tags_validated = [];
        foreach ($data['tags'] as $tag) {
            if (in_array($tag, $tags_available)) {
                $tags_validated[] = $tag;
            }
        }
        $data['tags'] = implode(',', $tags_validated);

        $data['members'] = implode(',', $team->users()->pluck('name')->toArray());
        $recruit = new Recruit($data);
        $recruit = $team->recruits()->save($recruit);
        return $recruit;
    }

    /**
     * 修改招募信息
     *
     * @param Recruit $recruit
     * @param Request $request
     *
     * @return Recruit
     * @throws CustomException
     */
    public function update(Recruit $recruit, Request $request)
    {
        $user = Auth::user();
        if (!$recruit->team->isLeader($user->id)) {
            throw new CustomException('当前用户不是这支队伍的管理员');
        }

        // TODO 禁止编辑当前成员
        $data = $request->validate([
            'tags' => 'required',
            'members' => '',
            'description' => 'required',
            'contact' => 'required',
        ]);
        if (!is_array($data['tags'])) {
            $data['tags'] = explode(',', $data['tags']);
        }

        // 去除非法标签
        $tags_available = config('mcm.recruit_tags');
        $tags_validated = [];
        foreach ($data['tags'] as $tag) {
            if (in_array($tag, $tags_available)) {
                $tags_validated[] = $tag;
            }
        }
        $data['tags'] = implode(',', $tags_validated);

        $recruit->update($data);
        return $recruit;
    }

    /**
     * 删除招募信息
     *
     * @param Recruit $recruit
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws CustomException
     * @throws \Exception
     */
    public function destroy(Recruit $recruit)
    {
        $user = Auth::user();
        if (!$recruit->team->isLeader($user->id)) {
            throw new CustomException('当前用户不是这支队伍的管理员');
        }
        $recruit->delete();
        return $recruit;
    }
}
