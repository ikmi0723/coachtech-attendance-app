<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者 勤怠一覧</title>
    <link rel="stylesheet" href="{{ asset('css/admin-attendance-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.admin-header')

    <main class="admin-attendance-list-main">
        <section class="admin-attendance-list-content">
            <h1 class="admin-attendance-list-content__title">勤怠一覧</h1>

            <div class="admin-attendance-list-date-nav">
                <a href="{{ route('admin.attendance.list', ['date' => $previousDate]) }}" class="admin-attendance-list-date-nav__link">
                    ← 前日
                </a>

                <p class="admin-attendance-list-date-nav__current">
                    {{ $targetDate->format('Y/m/d') }}
                </p>

                <a href="{{ route('admin.attendance.list', ['date' => $nextDate]) }}" class="admin-attendance-list-date-nav__link">
                    翌日 →
                </a>
            </div>

            <table class="admin-attendance-list-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffAttendances as $staffAttendance)
                    <tr>
                        <td>{{ $staffAttendance['name'] }}</td>
                        <td>{{ $staffAttendance['clock_in'] }}</td>
                        <td>{{ $staffAttendance['clock_out'] }}</td>
                        <td>{{ $staffAttendance['break_time'] }}</td>
                        <td>{{ $staffAttendance['work_time'] }}</td>
                        <td>
                            @if (\Carbon\Carbon::parse($staffAttendance['date_string'])->lte(\Carbon\Carbon::today()))
                            <a
                                href="{{ route('admin.attendance.detail', ['id' => $staffAttendance['attendance_id'], 'user_id' => $staffAttendance['user_id'], 'date' => $staffAttendance['date_string']]) }}"
                                class="admin-attendance-list-table__detail-link">
                                詳細
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>