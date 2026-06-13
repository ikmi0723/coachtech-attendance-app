<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            // 直近1か月分、当日なし
            $startDate = Carbon::today()->subMonth();
            $endDate = Carbon::yesterday();

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // 土日は除外したい場合
                if ($date->isWeekend()) {
                    continue;
                }

                $dayOfWeek = $date->dayOfWeekIso;

                if ($dayOfWeek === 1) { // 月
                    $clockInAt = $date->copy()->setTime(9, 0);
                    $clockOutAt = $date->copy()->setTime(18, 0);
                    $breaks = [
                        ['start' => [12, 0], 'end' => [13, 0]],
                    ];
                } elseif ($dayOfWeek === 2) { // 火
                    $clockInAt = $date->copy()->setTime(8, 30);
                    $clockOutAt = $date->copy()->setTime(17, 30);
                    $breaks = [
                        ['start' => [12, 15], 'end' => [13, 0]],
                    ];
                } elseif ($dayOfWeek === 3) { // 水
                    $clockInAt = $date->copy()->setTime(9, 30);
                    $clockOutAt = $date->copy()->setTime(18, 30);
                    $breaks = [
                        ['start' => [12, 0], 'end' => [13, 0]],
                    ];
                } elseif ($dayOfWeek === 4) { // 木
                    $clockInAt = $date->copy()->setTime(10, 0);
                    $clockOutAt = $date->copy()->setTime(19, 0);
                    $breaks = [];
                } else { // 金
                    $clockInAt = $date->copy()->setTime(8, 45);
                    $clockOutAt = $date->copy()->setTime(17, 45);
                    $breaks = [
                        ['start' => [12, 0], 'end' => [13, 15]],
                    ];
                }

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'work_date' => $date->toDateString(),
                    'clock_in_at' => $clockInAt,
                    'clock_out_at' => $clockOutAt,
                    'note' => '通常勤務',
                ]);

                foreach ($breaks as $break) {
                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start_at' => $date->copy()->setTime($break['start'][0], $break['start'][1]),
                        'break_end_at' => $date->copy()->setTime($break['end'][0], $break['end'][1]),
                    ]);
                }
            }
        }
    }
}
