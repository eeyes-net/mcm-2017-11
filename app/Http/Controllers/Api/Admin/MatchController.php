<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\MatchesTableUpdated;
use App\Exceptions\MatchDataException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Libraries\AssignTeamNumber;
use App\Libraries\MatchDataExport;
use App\Match;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MatchController extends Controller
{
    public function index()
    {
        return Match::latest()->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'expired_at' => 'required|date',
            'status' => 'required',
        ]);
        if ($data['status'] !== 'open') {
            $data['status'] = 'close';
        }
        $match = Match::create($data);
        event(new MatchesTableUpdated());
        return $match;
    }

    public function show(Match $match)
    {
        return $match;
    }

    public function team(Match $match)
    {
        return $match->teams()->with('users')->paginate();
    }

    public function apply(Request $request, Match $match)
    {
        $team_id = $request->post('team_id');
        $team = Team::find($team_id);
        $match->teams()->save($team);
        return $match;
    }

    public function detach(Match $match, Team $team)
    {
        $match->teams()->detach($team);
        return $match;
    }

    public function update(Request $request, Match $match)
    {
        $data = $request->only([
            'title',
            'expired_at',
            'status',
        ]);
        if (isset($data['status']) && $data['status'] !== 'open') {
            $data['status'] = 'close';
        }
        $match->update($data);
        event(new MatchesTableUpdated());
        return $match;
    }

    public function destroy(Match $match)
    {
        $match->delete();
        event(new MatchesTableUpdated());
        return $match;
    }

    public function allocNumber(Match $match)
    {
        $assignTeamNumber = new AssignTeamNumber($match);
        if (!$assignTeamNumber->assign()) {
            return new ArrayResource([
                'message' => '分配编号成功，但数据存在问题',
                'errors' => $assignTeamNumber->getErrors(),
            ]);
        }
        return new ArrayResource([
            'message' => '分配编号成功',
            'errors' => [],
        ]);
    }

    public function snapshot(Match $match)
    {
        $errors = [];
        try {
            $spreadsheet = MatchDataExport::matchToSpreadsheet($match);
        } catch (MatchDataException $e) {
            $errors = $e->getErrors();
            $spreadsheet = $e->getSpreadsheet();
        }
        $path = MatchDataExport::getPath($match);
        Storage::disk('match_snapshot')->put($path['path'], '');
        $writer = new Xlsx($spreadsheet);
        $writer->save(Storage::disk('match_snapshot')->path($path['path']));
        return new ArrayResource([
            'errors' => $errors,
            'filename' => $path['path'],
        ]);
    }
}
