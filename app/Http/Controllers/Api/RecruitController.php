<?php

namespace App\Http\Controllers\Api;

use App\Events\EvilUserInput;
use App\Exceptions\CustomException;
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
    public function currentUser(Request $request)
    {
        $user = Auth::user();
        $team_ids = $user->teams()->wherePivot('position', 'leader')->pluck('teams.id');
        $recruits = Recruit::whereIn('team_id', $team_ids)->get();
        return $recruits;
    }

    /**
     * 发布招募信息
     *
     * @param Request $request
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws CustomException
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
        if ($team->users()->count() >= 3) { // TODO not use hard code 3
            event(new EvilUserInput());
        }

        $data = $request->validate([
            'tags' => 'required',
            // 'members' => 'required',
            'description' => 'required',
            'contact' => 'required',
        ]);
        if (!is_array($data['tags'])) {
            $data['tags'] = explode(',', $data['tags']);
        }

        // 检查标签是否合法
        $tags_available = config('mcm.recruit_tags');
        foreach ($data['tags'] as $tag) {
            if (!in_array($tag, $tags_available)) {
                event(new EvilUserInput());
            }
        }

        $data['tags'] = implode(',', $data['tags']);
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
        $recruit->update($request->only([
            'tags',
            'members',
            'description',
            'contact',
        ]));
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
