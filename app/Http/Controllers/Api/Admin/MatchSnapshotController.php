<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Match;
use App\MatchSnapshot;
use App\MatchSnapshotUser;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MatchSnapshotController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('match_id')) {
            // TODO don't use (int) cast
            return Match::find((int)$request->get('match_id'))->snapshots()->latest()->paginate();
        }
        return MatchSnapshot::latest()->paginate();
    }

    public function show(MatchSnapshot $match_snapshot)
    {
        return $match_snapshot;
    }

    public function user(MatchSnapshot $match_snapshot)
    {
        return $match_snapshot->users()->paginate();
    }

    public function export(MatchSnapshot $matchSnapshot)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $base_row = 1;
        $sheet->setCellValue('A' . $base_row, '序号');
        $sheet->setCellValue('B' . $base_row, '队伍编号');
        $positions = [
            '队长',
            '队员1',
            '队员2',
        ];
        $fields = [
            'stu_id' => '学号',
            'name' => '姓名',
            'class' => '班级',
            'email' => '邮箱',
            'contact' => '联系方式',
            'department' => '学院',
            'major' => '专业',
        ];
        $column = 3;
        foreach ($positions as $position) {
            foreach ($fields as $field) {
                $sheet->setCellValueByColumnAndRow($column++, $base_row, $position . $field);
            }
        }

        $index = 0;
        $column = 1;
        $last_team_id = 0;
        $matchSnapshot->users()->orderBy('team_id', 'asc')->orderBy('position', 'asc')->orderBy('user_id', 'asc')->chunk(100, function ($users) use (&$sheet, &$index, &$column, &$last_team_id, &$fields) {
            $base_row = 1;
            /** @var MatchSnapshotUser $user */
            foreach ($users as $user) {
                if ($user->team_id !== $last_team_id) {
                    $last_team_id = $user->team_id;
                    ++$index;
                    $column = 1;
                    $sheet->setCellValueByColumnAndRow($column++, $base_row + $index, $index);
                    $sheet->setCellValueByColumnAndRow($column++, $base_row + $index, $user->team_id);
                }
                // TODO check is leader
                foreach ($fields as $key => $field) {
                    $sheet->getCellByColumnAndRow($column++, $base_row + $index)->setValueExplicit($user->$key, DataType::TYPE_STRING);
                }
            }
        });

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        // TODO avoid using output buffer
        return response(ob_get_clean(), 200, [
            'Content-Disposition' => 'attachment; filename=' . urlencode($matchSnapshot->title . '_' . $matchSnapshot->created_at->format('Ymd_His') . '.xlsx'),
            'Content-Type' => 'octet-stream',
        ]);
    }
}
