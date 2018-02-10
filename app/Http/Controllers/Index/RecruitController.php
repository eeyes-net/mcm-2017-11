<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Recruit;
use Illuminate\Http\Request;

class RecruitController extends Controller
{
    public function index(Request $request)
    {
        $tags = $request->get('tags');
        $tags = explode(',', $tags);
        $query = new Recruit();
        foreach ($tags as $tag) {
            $query = $query->orWhere('tags', 'like', "%{$tag}%");
        }
        $recruits = $query->latest()->paginate(12);
        return view('index.recruit.index', [
            'tags' => $tags,
            'recruits' => $recruits,
        ]);
    }
}
