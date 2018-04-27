<?php

namespace App\Libraries;

use App\Exceptions\TeamNumberException;
use App\Match;
use App\Team;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class AssignTeamNumber
{
    protected $match;
    protected $errors = [];
    protected $allTeamsNumber = null;
    protected $teamsNumber;
    protected $teamNumberAutoIncrement = null;
    protected $assignTeamNumberLock = null;

    public function __construct(Match $match)
    {
        $this->match = $match;
    }

    public function formatAllTeamNumber()
    {
        $this->errors = [];
        $this->match->teams()->chunk(100, function ($teams) use (&$errors) {
            foreach ($teams as $team) {
                try {
                    $team->team_id = self::formatTeamNumber($team->team_id);
                } catch (TeamNumberException $e) {
                    if (starts_with($e->getMessage(), 'Team number is not numeric')) {
                        $this->errors[] = __('队伍 :team_number（ID = :team_id）的编号不是数字', [
                            'team_number' => $team->team_id,
                            'team_id' => $team->id,
                        ]);
                    } elseif (starts_with($e->getMessage(), 'Team number is more than 4 digits')) {
                        $this->errors[] = __('队伍 :team_number（ID = :team_id）的编号长度大于 4 位数', [
                            'team_number' => $team->team_id,
                            'team_id' => $team->id,
                        ]);
                    } else {
                        throw $e;
                    }
                }
                $team->save();
            }
        });
        return empty($this->errors);
    }

    public static function formatTeamNumber($number)
    {
        $number = self::parseTeamNumber($number);
        if ($number > 9999) {
            throw new TeamNumberException('Team number is more than 4 digits');
        }
        return sprintf(config('mcm.team_number_format'), $number);
    }

    public static function parseTeamNumber($number)
    {
        if (!is_numeric($number)) {
            throw new TeamNumberException('Team number is not numeric');
        }
        return (int)$number;
    }

    public function assignAllTeams()
    {
        // TODO use lock
        $this->errors = [];
        $this->teamsNumber = [];
        $this->loadAllTeamsNumber();
        $this->loadTeamNumberAutoIncrement();
        $this->match->teams()->chunk(100, function ($teams) {
            foreach ($teams as $team) {
                $this->assign($team);
            }
        });
        return empty($this->errors);
    }

    protected function loadAllTeamsNumber()
    {
        if (is_null($this->allTeamsNumber)) {
            $this->allTeamsNumber = $this->parseTeamNumberArray(
                $this->match->teams()
                    ->where('teams.team_id', '>', "''")// not empty
                    ->pluck('teams.team_id')
                    ->toArray()
            );
        }
    }

    protected function loadTeamNumberAutoIncrement()
    {
        if (is_null($this->teamNumberAutoIncrement)) {
            $this->teamNumberAutoIncrement = self::getTeamNumberAutoIncrement();
        }
    }

    protected function assign(Team $team)
    {
        $team_number = null;
        try {
            $team_number = self::parseTeamNumber($team->team_id);
        } catch (TeamNumberException $e) {
            if (starts_with($e->getMessage(), 'Team number is not numeric')) {
                $team_number = '';
                if (!empty($team_number)) {
                    $this->errors[] = __('队伍 :team_number（ID = :team_id）的编号不是数字', [
                        'team_number' => $team_number,
                        'team_id' => $team->id,
                    ]);
                }
            } else {
                throw $e;
            }
        }
        if (empty($team_number) || in_array($team_number, $this->teamsNumber)) {
            $this->findTeamNumberAutoIncrement();
            $team->team_id = $this->teamNumberAutoIncrement;
            $this->allTeamsNumber[] = $this->teamNumberAutoIncrement;
            ++$this->teamNumberAutoIncrement;
            $this->saveTeamNumberAutoIncrement();
        }
        $team->team_id = self::formatTeamNumber($team->team_id);
        $team->save();
        $this->teamsNumber[] = $team->team_id;
        return $team;
    }

    protected function parseTeamNumberArray(array $numbers)
    {
        return array_map(function ($item) {
            try {
                return self::parseTeamNumber($item);
            } catch (TeamNumberException $e) {
                if (starts_with($e->getMessage(), 'Team number is not numeric')) {
                    $this->errors[] = __('队伍编号 :team_number 不是数字', [
                        'team_number' => $item,
                    ]);
                }
                return $item;
            }
        }, $numbers);
    }

    public static function getTeamNumberAutoIncrement()
    {
        try {
            return (int)Storage::drive('local')->get('team_number_auto_increment');
        } catch (FileNotFoundException $e) {
            return 1;
        }
    }

    protected function findTeamNumberAutoIncrement()
    {
        for (; in_array($this->teamNumberAutoIncrement, $this->allTeamsNumber); ++$this->teamNumberAutoIncrement) {
            // Nothing to do.
        }
        return $this->teamNumberAutoIncrement;
    }

    protected function saveTeamNumberAutoIncrement()
    {
        return self::putTeamNumberAutoIncrement($this->teamNumberAutoIncrement);
    }

    public static function putTeamNumberAutoIncrement($value)
    {
        return \Storage::drive('local')->put('team_number_auto_increment', $value);
    }

    public function assignOneTeam(Team $team)
    {
        // TODO use lock
        $this->errors = [];
        $this->teamsNumber = [];
        $this->loadAllTeamsNumber();
        $this->loadTeamNumberAutoIncrement();
        $same_team_number_count = Team::where('team_id', $team->team_id)->count();
        if ($same_team_number_count > 1) {
            // reassign team number
            $team->team_id = '';
        }
        $this->assign($team);
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}