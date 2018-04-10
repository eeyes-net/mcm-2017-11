<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruit\Destroy;
use App\Http\Requests\Recruit\Store;
use App\Http\Requests\Recruit\Update;
use App\Http\Resources\Recruit as RecruitResource;
use App\Http\Resources\RecruitTags;
use App\Recruit;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitController extends Controller
{
    /**
     * 所有招募信息
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // 目前仅允许查询单个标签
        // TODO 允许多个标签
        if ($request->has('tags')) {
            $data = $request->validate([
                'tags' => 'in:' . implode(',', config('mcm.recruit_tags', [])),
            ]);
            $tag = $data['tags'];
            // TODO cache
            // $recruits = Cache::tags('recruits')->remember('api_recruit_tag_' . $tag . '_page_' . request('page'), 1440, function () use ($tag) {
            return RecruitResource::collection(Recruit::where('tags', 'like', "%{$tag}%")->latest()->paginate(12));
            // });
        } else {
            // $recruits = Cache::tags('recruits')->remember('api_recruit_page_' . request('page'), 1440, function () {
            return RecruitResource::collection(Recruit::latest()->paginate(12));
            // });
        }
        // return $recruits;
    }

    /**
     * 当前用户管理的队伍的招募信息
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function currentUser()
    {
        $user = Auth::user();
        // 只有队长显示自己队伍的招募
        $team_ids = $user->teams()->wherePivot('position', Team::USER_POSITION_LEADER)->pluck('teams.id');
        $recruits = Recruit::whereIn('team_id', $team_ids)->get();
        return RecruitResource::collection($recruits);
    }

    /**
     * 获取所有标签
     *
     * @return \App\Http\Resources\RecruitTags
     */
    public function tags()
    {
        return new RecruitTags(config('mcm.recruit_tags', []));
    }

    /**
     * 发布招募信息
     *
     * @param \App\Http\Requests\Recruit\Store $request
     *
     * @return \App\Http\Resources\Recruit
     */
    public function store(Store $request)
    {
        /** @var \App\Team $team */
        $team = $request->get('team');
        $data = $request->only([
            'description',
            'contact',
        ]);
        $data['tags'] = implode(',', $request->post('tags'));
        $data['members'] = implode(',', $team->users()->pluck('name')->toArray());
        $recruit = new Recruit($data);
        $recruit = $team->recruits()->save($recruit);
        return new RecruitResource($recruit);
    }

    /**
     * 修改招募信息
     *
     * @param \App\Http\Requests\Recruit\Update $request
     * @param \App\Recruit $recruit
     *
     * @return \App\Http\Resources\Recruit
     */
    public function update(Update $request, Recruit $recruit)
    {
        /** @var \App\Team $team */
        $team = $request->get('team');
        $data = $request->only([
            'description',
            'contact',
        ]);
        $data['tags'] = implode(',', $request->post('tags'));
        $data['members'] = implode(',', $team->users()->pluck('name')->toArray());
        $recruit->update($data);
        return new RecruitResource($recruit);
    }

    /**
     * 删除招募信息
     *
     * @param \App\Http\Requests\Recruit\Destroy $request
     * @param \App\Recruit $recruit
     *
     * @return \App\Http\Resources\Recruit
     * @throws \Exception
     */
    public function destroy(Destroy $request, Recruit $recruit)
    {
        $recruit->delete();
        return new RecruitResource($recruit);
    }
}
