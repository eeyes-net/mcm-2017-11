<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\MatchesTableUpdated;
use App\Exceptions\MatchDataException;
use App\Http\Controllers\Controller;
use App\Libraries\MatchDataExport;
use App\Match;
use App\Team;
use Carbon\Carbon;
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
        $team_numbers = [];
        $team_numbers_all = $match->teams()->where('teams.team_id', '>', "''")->pluck('teams.team_id')->toArray();
        $team_count = $match->teams()->count();
        $team_number_available = 1;
        $match->teams()->orderBy('id', 'asc')->chunk(100, function ($teams) use (&$team_numbers, &$team_numbers_all, &$team_count, &$team_number_available) {
            foreach ($teams as $team) {
                $team_number = $team->team_id;
                if (empty($team_number) || in_array($team_number, $team_numbers)) {
                    // TODO optimise algorithm
                    for (; $team_number_available <= $team_count; ++$team_number_available) {
                        $team_number_new = (string)$team_number_available;
                        if (!in_array($team_number_new, $team_numbers_all)) {
                            $team->team_id = $team_number_new;
                            $team_numbers_all[] = $team_number_new;
                            $team->save();
                            ++$team_number_available;
                            break;
                        }
                    }
                }
                $team_numbers[] = $team_number;
            }
        });
        return $match;
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
        $datetime = Carbon::now()->format('Ymd_His');
        $filename = "Match_{$match->id}_{$match->title}_{$datetime}.xlsx";
        $path = Storage::disk('match_snapshot')->path($filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return [
            'errors' => $errors,
            'filename' => $filename,
        ];
    }
}
