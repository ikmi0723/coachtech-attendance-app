<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠一覧</title>
    <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.user-header')

    <main class="attendance-list-main">
        <section class="attendance-list-content">
            <h1 class="attendance-list-content__title">勤怠一覧</h1>

            <div class="attendance-list-month-nav">
                <a href="{{ route('attendance.list', ['month' => $previousMonth]) }}" class="attendance-list-month-nav__link">
                    ← 前月
                </a>

                <p class="attendance-list-month-nav__current">
                    {{ $currentMonth->format('Y/m') }}
                </p>

                <a href="{{ route('attendance.list', ['month' => $nextMonth]) }}" class="attendance-list-month-nav__link">
                    翌月 →
                </a>
            </div>

            <table class="attendance-list-table">
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
                            @if ($day['date']->lte(\Carbon\Carbon::today()))
                            <a
                                href="{{ route('attendance.detail', ['id' => $day['attendance_id'], 'date' => $day['date_string']]) }}"
                                class="attendance-list-table__detail-link">
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