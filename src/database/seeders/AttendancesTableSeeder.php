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

                $clockInAt = $date->copy()->setTime(9, 0);
                $clockOutAt = $date->copy()->setTime(18, 0);

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'work_date' => $date->toDateString(),
                    'clock_in_at' => $clockInAt,
                    'clock_out_at' => $clockOutAt,
                    'note' => '通常勤務',
                ]);

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start_at' => $date->copy()->setTime(12, 0),
                    'break_end_at' => $date->copy()->setTime(13, 0),
                ]);
            }
        }
    }
}
