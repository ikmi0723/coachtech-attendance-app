<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠詳細</title>
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
</head>

<body>
    <header class="attendance-detail-header">
        <div class="attendance-detail-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="attendance-detail-header__logo">

            <nav class="attendance-detail-header__nav">
                <a href="/attendance" class="attendance-detail-header__link">勤怠</a>
                <a href="/attendance/list" class="attendance-detail-header__link">勤怠一覧</a>
                <a href="#" class="attendance-detail-header__link">申請</a>

                <form method="POST" action="{{ route('logout') }}" class="attendance-detail-header__logout-form">
                    @csrf
                    <button type="submit" class="attendance-detail-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="attendance-detail-main">
        <section class="attendance-detail-content">
            <h1 class="attendance-detail-content__title">勤怠詳細</h1>

            @if (session('message'))
            <p class="attendance-detail-alert attendance-detail-alert--success">{{ session('message') }}</p>
            @endif

            @if ($errors->any())
            <div class="attendance-detail-alert attendance-detail-alert--error">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('attendance.detail.update', ['id' => $attendance->id]) }}">
                @csrf

                <div class="attendance-detail-card">
                    <div class="attendance-detail-row">
                        <p class="attendance-detail-row__label">日付</p>
                        <p class="attendance-detail-row__value">{{ $displayDate }}</p>
                    </div>

                    <div class="attendance-detail-row">
                        <p class="attendance-detail-row__label">出勤・退勤</p>

                        <div class="attendance-detail-time-pair">
                            <input
                                type="time"
                                name="clock_in"
                                class="attendance-detail-time-pair__input"
                                value="{{ old('clock_in', $clockIn) }}"
                                @if($isFutureDate) disabled @endif>
                            <span class="attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="time"
                                name="clock_out"
                                class="attendance-detail-time-pair__input"
                                value="{{ old('clock_out', $clockOut) }}"
                                @if($isFutureDate) disabled @endif>
                        </div>
                    </div>

                    @foreach ($breakInputs as $index => $breakInput)
                    <div class="attendance-detail-row">
                        <p class="attendance-detail-row__label">休憩{{ $index + 1 }}</p>

                        <div class="attendance-detail-time-pair">
                            <input
                                type="time"
                                name="breaks[{{ $index }}][break_start_at]"
                                class="attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_start_at", $breakInput['break_start_at']) }}"
                                @if($isFutureDate) disabled @endif>
                            <span class="attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="time"
                                name="breaks[{{ $index }}][break_end_at]"
                                class="attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_end_at", $breakInput['break_end_at']) }}"
                                @if($isFutureDate) disabled @endif>
                        </div>
                    </div>
                    @endforeach

                    <div class="attendance-detail-row attendance-detail-row--textarea">
                        <p class="attendance-detail-row__label">備考</p>

                        <textarea
                            name="note"
                            class="attendance-detail-textarea"
                            @if($isFutureDate) disabled @endif>{{ old('note', $attendance->note) }}</textarea>
                    </div>
                </div>

                <div class="attendance-detail-action">
                    <button
                        type="submit"
                        class="attendance-detail-action__button"
                        @if($isFutureDate || $hasPendingRequest) disabled @endif>
                        修正
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>