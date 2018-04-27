<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\EvilInputException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class MatchSnapshotController extends Controller
{
    public function index()
    {
        $files = Storage::disk('match_snapshot')->allFiles();
        $files = collect($files)->reverse()->map(function ($item) {
            $parts = explode('/', $item);
            return [
                'path' => $item,
                'filename' => array_pop($parts),
            ];
        })->values();
        $items = $files->forPage(Paginator::resolveCurrentPage(), 15)->values()->all();
        $pagination = new LengthAwarePaginator($items, count($files), 15);
        return $pagination;
    }

    public function download(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required',
        ]);
        $filename = $request->query('filename');
        $path = Storage::disk('match_snapshot')->path($filename);
        $path = realpath($path);
        if (!$path) {
            abort(404);
            return null;
        }
        $base_path = Storage::disk('match_snapshot')->path('/');
        $base_path = realpath($base_path) . DIRECTORY_SEPARATOR;
        if (!starts_with($path, $base_path)) {
            throw new EvilInputException(__('下载非竞赛快照目录下的文件 :filename', [
                'filename' => $filename,
            ]));
        }
        return response()->download($path);
    }
}
