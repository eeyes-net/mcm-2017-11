<?php

namespace App\Libraries;

use App\Exceptions\MatchDataException;
use App\Match;
use App\Team;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MatchDataExport
{
    public static function highlightRow(Worksheet $sheet, $row, $argb = 'FFFF0000')
    {
        $sheet->getStyle($row . ':' . $row)
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($argb);
    }

    public static function matchToSpreadsheet(Match $match)
    {
        // ============================== 常量 ==============================
        $team_users_count_limit = config('mcm.team_users_count_limit');
        $fields = [
            'name' => '姓名',
            'stu_id' => '学号',
            'class' => '班级',
            'email' => '邮箱',
            'contact' => '联系方式',
            'department' => '学院',
            'major' => '专业',
        ];
        $position_map = [
            Team::USER_POSITION_LEADER => '队长',
            Team::USER_POSITION_MEMBER => '队员',
        ];
        $positions = [
            $position_map[Team::USER_POSITION_LEADER],
        ];
        for ($i = 1; $i < $team_users_count_limit; ++$i) {
            $positions[] = $position_map[Team::USER_POSITION_MEMBER] . $i;
        }

        // ============================== 对象 ==============================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('报名信息');

        // ============================== 表头 ==============================
        $row = 1;
        $column = 1;
        $sheet->setCellValueByColumnAndRow($column++, $row, '序号');
        $sheet->setCellValueByColumnAndRow($column++, $row, '队伍ID');
        $sheet->setCellValueByColumnAndRow($column++, $row, '队伍编号');
        foreach ($positions as $position) {
            foreach ($fields as $key => $field) {
                if ($key === 'name') {
                    $sheet->setCellValueByColumnAndRow($column++, $row, $position . $field);
                } else {
                    $sheet->setCellValueByColumnAndRow($column++, $row, $field);
                }
            }
        }

        // ============================== 数据 ==============================
        $errors = [];
        $users_team = [];
        $index = 0;
        $match->teams()->chunk(100, function ($teams) use (&$sheet, &$column, &$row, &$index, &$fields, &$users_team, &$errors, $team_users_count_limit) {
            /** @var \App\Team $team */
            foreach ($teams as $team) {
                // ============================== 新的一行 ==============================
                ++$index;
                ++$row;

                // ============================== 异常行为检测 ==============================
                $users = $team->users()->get();
                if ($users->isEmpty()) {
                    $errors[] = __('队伍 :team_number（ID = :team_id）没有成员', [
                        'team_number' => $team->number,
                        'team_id' => $team->id,
                    ]);
                    self::highlightRow($sheet, $row);
                } elseif ($users->count() > $team_users_count_limit) {
                    $errors[] = __('队伍 :team_number（ID = :team_id）的成员数量 :count 个，超过上限 :limit', [
                        'team_number' => $team->number,
                        'team_id' => $team->id,
                        'count' => $users->count(),
                        'limit' => $team_users_count_limit,
                    ]);
                    self::highlightRow($sheet, $row);
                }

                // ============================== 用户身份排序 ==============================
                $users = $users->sortBy(function ($user, $key) use (&$team, &$errors, &$sheet, &$row) {
                    switch ($user->pivot->position) {
                        case Team::USER_POSITION_LEADER:
                            return 1;
                        case Team::USER_POSITION_MEMBER:
                            return 2;
                        default:
                            // 一些异常行为
                            $errors[] = __('队伍 :team_number（ID = :team_id）的成员 :name （ID = :user_id, 学号 = :stu_id）的身份 :position 异常', [
                                'team_number' => $team->number,
                                'team_id' => $team->id,
                                'name' => $user->name,
                                'user_id' => $user->id,
                                'stu_id' => $user->stu_id,
                                'position' => $user->pivot->position,
                            ]);
                            self::highlightRow($sheet, $row);
                            return 3;
                    }
                })->values();

                // ============================== 写入数据 ==============================
                $column = 1;
                $sheet->setCellValueByColumnAndRow($column++, $row, $index);
                $sheet->setCellValueByColumnAndRow($column++, $row, $team->id);
                $sheet->setCellValueByColumnAndRow($column++, $row, $team->number);
                foreach ($users as $i => $user) {
                    // ============================== 异常行为检测 ==============================
                    if (isset($users_team[$user->id])) {
                        $errors[] = __('队伍 :team_number（ID = :team_id）的成员 :name（ID = :user_id, 学号 = :stu_id）的已代表队伍 :team_number_1（ID = :team_id_1）参加比赛', [
                            'team_number' => $team->number,
                            'team_id' => $team->id,
                            'name' => $user->name,
                            'user_id' => $user->id,
                            'stu_id' => $user->stu_id,
                            'team_number_1' => $users_team[$user->id]['number'],
                            'team_id_1' => $users_team[$user->id]['id'],
                        ]);
                        self::highlightRow($sheet, $row);
                    } else {
                        $users_team[$user->id] = [
                            'number' => $team->number,
                            'id' => $team->id,
                        ];
                    }
                    if ($i === 0) {
                        if ($user->pivot->position !== Team::USER_POSITION_LEADER) {
                            $errors[] = __('队伍 :team_number（ID = :team_id）的第 :i 名成员 :name（ID = :user_id, 学号 = :stu_id）的身份 :position 不是队长', [
                                'team_number' => $team->number,
                                'team_id' => $team->id,
                                'i' => $i + 1,
                                'name' => $user->name,
                                'user_id' => $user->id,
                                'stu_id' => $user->stu_id,
                                'position' => $user->pivot->position,
                            ]);
                            self::highlightRow($sheet, $row);
                        }
                    } elseif ($user->pivot->position !== Team::USER_POSITION_MEMBER) {
                        $errors[] = __('队伍 :team_number（ID = :team_id）的第 :i 名成员 :name（ID = :user_id, 学号 = :stu_id）的身份 :position 不是队员', [
                            'team_number' => $team->number,
                            'team_id' => $team->id,
                            'i' => $i + 1,
                            'name' => $user->name,
                            'user_id' => $user->id,
                            'stu_id' => $user->stu_id,
                            'position' => $user->pivot->position,
                        ]);
                        self::highlightRow($sheet, $row);
                    }
                    // ============================== 写入数据 ==============================
                    foreach ($fields as $key => $field) {
                        $sheet->getCellByColumnAndRow($column++, $row)->setValueExplicit($user->$key, DataType::TYPE_STRING);
                    }
                }
            }
        });

        // ============================== 竞赛信息 ==============================
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('竞赛信息');
        $fields = [
            'id' => 'ID',
            'title' => '标题',
            'expired_at' => '截止日期',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '最后修改时间',
        ];
        $row = 0;
        foreach ($fields as $key => $field) {
            ++$row;
            $sheet->setCellValueByColumnAndRow(1, $row, $field);
            $sheet->setCellValueByColumnAndRow(2, $row, $match->$key);
        }

        // ============================== 错误信息 ==============================
        if ($errors) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('错误信息');
            $row = 1;
            foreach ($errors as $error) {
                $sheet->getCellByColumnAndRow(1, $row++)->setValueExplicit($error, DataType::TYPE_STRING);
            }
            throw new MatchDataException($errors, $spreadsheet);
        }
        return $spreadsheet;
    }
}