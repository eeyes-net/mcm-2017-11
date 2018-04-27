<?php

use App\Libraries\MatchDataExport;
use App\MatchSnapshot;
use App\MatchSnapshotUser;
use App\Team;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DropMatchSnapshotsTable extends Migration
{
    public static function exportSpreadsheet(MatchSnapshot $match_snapshot)
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
        $match_snapshot->users()->orderBy('team_id', 'asc')->orderBy('position', 'asc')->orderBy('user_id', 'asc')->chunk(100, function ($users) use (&$sheet, &$index, &$column, &$last_team_id, &$fields) {
            $base_row = 1;
            /** @var MatchSnapshotUser $user */
            foreach ($users as $user) {
                if ($user->team_id !== $last_team_id) {
                    $last_team_id = $user->team_id;
                    ++$index;
                    $column = 1;
                    $sheet->setCellValueByColumnAndRow($column++, $base_row + $index, $index);
                    $user_team = Team::find($user->team_id);
                    $sheet->setCellValueByColumnAndRow($column++, $base_row + $index, $user_team->team_id);
                }
                // TODO check is leader
                foreach ($fields as $key => $field) {
                    $sheet->getCellByColumnAndRow($column++, $base_row + $index)->setValueExplicit($user->$key, DataType::TYPE_STRING);
                }
            }
        });

        $writer = new Xlsx($spreadsheet);

        $datetime = Carbon::parse($match_snapshot->created_at)->format('Ymd_His');
        $filename = "{$datetime}_{$match_snapshot->title}.xlsx";
        $filename = MatchDataExport::sanitize_file_name($filename);
        $dir_name = $match_snapshot->match_id;
        $path = $dir_name . DIRECTORY_SEPARATOR . $filename;

        Storage::disk('match_snapshot')->put($path, '');
        $writer = new Xlsx($spreadsheet);
        $writer->save(Storage::disk('match_snapshot')->path($path));
        return $path;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        MatchSnapshot::chunk(100, function ($match_snapshots) {
            foreach ($match_snapshots as $match_snapshot) {
                self::exportSpreadsheet($match_snapshot);
            }
        });

        Schema::dropIfExists('match_snapshot_users');
        Schema::dropIfExists('match_snapshots');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('match_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('matches');
            $table->text('title')->comment('标题');
            $table->dateTime('expired_at')->comment('截止日期');
            $table->timestamp('match_created_at')->nullable()->comment('竞赛发布日期');
            $table->timestamp('match_updated_at')->nullable()->comment('竞赛最后日期');
            $table->timestamps();
        });
        Schema::create('match_snapshot_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_snapshot_id')->unsigned();
            $table->foreign('match_snapshot_id')->references('id')->on('match_snapshots');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->string('team_number', 50)->comment('队伍编号');
            $table->string('username', 190)->comment('用户名NetID');
            $table->text('stu_id')->comment('学号');
            $table->text('name')->comment('姓名');
            $table->text('department')->comment('学院');
            $table->text('major')->comment('专业');
            $table->text('class')->comment('班级');
            $table->text('contact')->comment('联系方式');
            $table->string('email', 190)->comment('邮箱');
            $table->string('position', 20)->comment('身份');
            $table->timestamps();
        });
    }
}
