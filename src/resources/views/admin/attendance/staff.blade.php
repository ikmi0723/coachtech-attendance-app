<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ別勤怠一覧</title>
    <link rel="stylesheet" href="{{ asset('css/admin-staff-attendance.css') }}">
</head>

<body>
    <header class="admin-staff-attendance-header">
        <div class="admin-staff-attendance-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="admin-staff-attendance-header__logo">

            <nav class="admin-staff-attendance-header__nav">
                <a href="/admin/attendance/list" class="admin-staff-attendance-header__link">勤怠一覧</a>
                <a href="/admin/staff/list" class="admin-staff-attendance-header__link">スタッフ一覧</a>
                <a href="/admin/stamp_correction_request/list" class="admin-staff-attendance-header__link">申請一覧</a>

                <form method="POST" action="{{ route('logout') }}" class="admin-staff-attendance-header__logout-form">
                    @csrf
                    <button type="submit" class="admin-staff-attendance-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="admin-staff-attendance-main">
        <section class="admin-staff-attendance-content">
            <h1 class="admin-staff-attendance-content__title">
                <span class="admin-staff-attendance-content__title-bar"></span>
                <span class="admin-staff-attendance-content__title-text">{{ $staff->name }}さんの勤怠</span>
            </h1>

            <div class="admin-staff-attendance-month-nav">
                <a
                    href="{{ route('admin.staff.attendance', ['id' => $staff->id, 'month' => $previousMonth]) }}"
                    class="admin-staff-attendance-month-nav__link">
                    ← 前月
                </a>

                <div class="admin-staff-attendance-month-nav__current">
                    <span class="admin-staff-attendance-month-nav__icon">🗓</span>
                    <span>{{ $currentMonth->format('Y/m') }}</span>
                </div>

                <a
                    href="{{ route('admin.staff.attendance', ['id' => $staff->id, 'month' => $nextMonth]) }}"
                    class="admin-staff-attendance-month-nav__link">
                    翌月 →
                </a>
            </div>

            <table class="admin-staff-attendance-table">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                    <tr>
                        <td>{{ $day['date']->format('m/d') }}({{ $day['date']->locale('ja')->translatedFormat('D') }})</td>
                        <td>{{ $day['clock_in'] }}</td>
                        <td>{{ $day['clock_out'] }}</td>
                        <td>{{ $day['break_time'] }}</td>
                        <td>{{ $day['work_time'] }}</td>
                        <td>
                            <a
                                href="{{ route('admin.attendance.detail', ['id' => $day['attendance_id'], 'user_id' => $staff->id, 'date' => $day['date_string']]) }}"
                                class="admin-staff-attendance-table__detail-link">
                                詳細
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="admin-staff-attendance-export">
                <a
                    href="{{ route('admin.staff.attendance.csv', ['id' => $staff->id, 'month' => $currentMonth->format('Y-m')]) }}"
                    class="admin-staff-attendance-export__button">
                    CSV出力
                </a>
            </div>
        </section>
    </main>
</body>

</html>