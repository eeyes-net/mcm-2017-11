<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Recruit;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitController extends Controller
{
    /**
     * 所有队友招募信息
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
     * 发布队友招募信息
     * @param Request $request
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws CustomException
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'team_id' => 'required|numeric',
        ]);
        $team = Team::find($data['team_id']);
        $user = $team->users()->wherePivot('position', 'leader')->first();
        if ($user->id !== Auth::user()->id) {
            throw new CustomException('这支队伍不由您来管理');
        }
        $data = $request->validate([
            'tags' => 'required',
            // 'members' => 'required',
            'description' => 'required',
            'contact' => 'required',
        ]);
        $data['members'] = implode(' ', $team->users()->pluck('name')->toArray());
        $recruit = new Recruit($data);
        $recruit = $team->recruits()->save($recruit);
        return $recruit;
    }

    /**
     * 删除队友招募信息
     * @param int $id 招募对应的ID
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws CustomException
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $recruit = Recruit::find($id);
        if (!$recruit || $recruit->user_id != $user->id) {
            throw new CustomException('不是当前用户');
        }
        $recruit->delete();
        return $recruit;
    }
}
