<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Recruit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class RecruitController extends Controller
{
    public function index(Request $request)
    {
        // 目前仅允许查询单个标签
        // TODO 允许多个标签
        if ($request->has('tags')) {
            $data = $request->validate([
                'tags' => 'in:' . implode(',', config('mcm.recruit_tags', [])),
            ]);
            $tag = $data['tags'];
            $recruits = Cache::tags('recruits')->remember('recruit_tag_' . $tag . '_page_' . Paginator::resolveCurrentPage(), 1440, function () use ($tag) {
                return Recruit::where('tags', 'like', "%{$tag}%")->latest()->paginate(12);
            });
            $tags = [$tag];
        } else {
            $recruits = Cache::tags('recruits')->remember('recruit_page_' . Paginator::resolveCurrentPage(), 1440, function () {
                return Recruit::latest()->paginate(12);
            });
            $tags = [];
        }
        return view('index.recruit.index', [
            'tags' => $tags,
            'recruits' => $recruits,
        ]);
    }
}
