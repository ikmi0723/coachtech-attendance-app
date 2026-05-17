<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠登録</title>

    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
</head>

<body>
    {{-- ヘッダー --}}
    <header class="attendance-header">
        <div class="attendance-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="attendance-header__logo">

            <nav class="attendance-header__nav">
                <a href="/attendance" class="attendance-header__link">勤怠</a>
                <a href="/attendance/list" class="attendance-header__link">勤怠一覧</a>
                <a href="/stamp_correction_request/list" class="attendance-header__link">申請</a>

                <form method="POST" action="{{ route('logout') }}" class="attendance-header__logout-form">
                    @csrf
                    <button type="submit" class="attendance-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="attendance-main">
        <div class="attendance-card">
            <p class="attendance-card__status">{{ $statusLabel }}</p>

            <p class="attendance-card__date" id="current-date"></p>
            <p class="attendance-card__time" id="current-time"></p>

            @if ($status === 'outside')
            <div class="attendance-card__button-wrapper">
                <form method="POST" action="{{ route('attendance.clock_in') }}">
                    @csrf
                    <button type="submit" class="attendance-card__button attendance-card__button--black">
                        出勤
                    </button>
                </form>
            </div>
            @elseif ($status === 'working')
            <div class="attendance-card__button-group">
                <form method="POST" action="{{ route('attendance.clock_out') }}">
                    @csrf
                    <button type="submit" class="attendance-card__button attendance-card__button--black">
                        退勤
                    </button>
                </form>
                <form method="POST" action="{{ route('attendance.break_start') }}">
                    @csrf
                    <button type="submit" class="attendance-card__button attendance-card__button--white">
                        休憩入
                    </button>
                </form>
            </div>
            @elseif ($status === 'break')
            <div class="attendance-card__button-wrapper">
                <form method="POST" action="{{ route('attendance.break_end') }}">
                    @csrf
                    <button type="submit" class="attendance-card__button attendance-card__button--white">
                        休憩戻
                    </button>
                </form>
            </div>
            @elseif ($status === 'finished')
            <p class="attendance-card__message">お疲れ様でした。</p>
            @endif
        </div>
    </main>
    {{-- 現在日時のリアルタイム表示 --}}
    <script>
        const baseNow = new Date('{{ $nowIso }}');
        const loadedAt = new Date();

        function formatDate(date) {
            const weekDays = ['日', '月', '火', '水', '木', '金', '土'];
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            const day = date.getDate();
            const weekDay = weekDays[date.getDay()];

            return `${year}年${month}月${day}日(${weekDay})`;
        }

        function formatTime(date) {
            const hour = String(date.getHours()).padStart(2, '0');
            const minute = String(date.getMinutes()).padStart(2, '0');

            return `${hour}:${minute}`;
        }

        function updateCurrentDateTime() {
            const now = new Date(baseNow.getTime() + (Date.now() - loadedAt.getTime()));

            document.getElementById('current-date').textContent = formatDate(now);
            document.getElementById('current-time').textContent = formatTime(now);
        }

        updateCurrentDateTime();
        setInterval(updateCurrentDateTime, 1000);
    </script>
</body>

</html>