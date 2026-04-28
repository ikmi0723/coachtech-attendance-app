<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::today()->subDays($i);

                // 土日はスキップしたい場合はコメント解除
                // if ($date->isWeekend()) {
                //     continue;
                // }

                // 一部の日は勤怠なしにして、一覧で空欄表示確認しやすくする
                if (in_array($i, [3, 8, 15, 22])) {
                    continue;
                }

                $clockIn = $date->copy()->setTime(9, 0);
                $clockOut = $date->copy()->setTime(18, 0);

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'work_date' => $date->toDateString(),
                    'clock_in_at' => $clockIn,
                    'clock_out_at' => $clockOut,
                    'note' => '通常勤務',
                ]);

                // 日によって休憩パターンを変える
                if ($i % 3 === 0) {
                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start_at' => $date->copy()->setTime(12, 0),
                        'break_end_at' => $date->copy()->setTime(13, 0),
                    ]);
                } elseif ($i % 3 === 1) {
                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start_at' => $date->copy()->setTime(12, 0),
                        'break_end_at' => $date->copy()->setTime(12, 30),
                    ]);

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start_at' => $date->copy()->setTime(15, 0),
                        'break_end_at' => $date->copy()->setTime(15, 15),
                    ]);
                }
                // $i % 3 === 2 の日は休憩なし
            }
        }
    }
}
