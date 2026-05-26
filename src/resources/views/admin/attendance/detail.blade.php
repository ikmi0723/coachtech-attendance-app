<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者 勤怠詳細</title>
    <link rel="stylesheet" href="{{ asset('css/admin-attendance-detail.css') }}">
</head>

<body>
    <header class="admin-attendance-detail-header">
        <div class="admin-attendance-detail-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="admin-attendance-detail-header__logo">

            <nav class="admin-attendance-detail-header__nav">
                <a href="/admin/attendance/list" class="admin-attendance-detail-header__link">勤怠一覧</a>
                <a href="#" class="admin-attendance-detail-header__link">スタッフ一覧</a>
                <a href="/stamp_correction_request/list" class="admin-attendance-detail-header__link">申請一覧</a>

                <form method="POST" action="{{ route('logout') }}" class="admin-attendance-detail-header__logout-form">
                    @csrf
                    <button type="submit" class="admin-attendance-detail-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="admin-attendance-detail-main">
        <section class="admin-attendance-detail-content">
            <h1 class="admin-attendance-detail-content__title">勤怠詳細</h1>

            @if (session('message'))
            <p class="admin-attendance-detail-alert admin-attendance-detail-alert--success">{{ session('message') }}</p>
            @endif

            @if ($errors->any())
            <div class="admin-attendance-detail-alert admin-attendance-detail-alert--error">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.attendance.update', ['id' => $attendance?->id ?? 0, 'user_id' => $detailUserId, 'date' => $detailDate]) }}">
                @csrf

                <div class="admin-attendance-detail-card">
                    <div class="admin-attendance-detail-row">
                        <p class="admin-attendance-detail-row__label">名前</p>
                        <p class="admin-attendance-detail-row__value">{{ $userName }}</p>
                    </div>

                    <div class="admin-attendance-detail-row">
                        <p class="admin-attendance-detail-row__label">日付</p>
                        <p class="admin-attendance-detail-row__value">{{ $displayDate }}</p>
                    </div>

                    <div class="admin-attendance-detail-row">
                        <p class="admin-attendance-detail-row__label">出勤・退勤</p>

                        <div class="admin-attendance-detail-time-pair">
                            <input
                                type="text"
                                name="clock_in"
                                class="admin-attendance-detail-time-pair__input"
                                value="{{ old('clock_in', $clockIn) }}"
                                placeholder="09:00"
                                @if($isFutureDate) disabled @endif>
                            <span class="admin-attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="text"
                                name="clock_out"
                                class="admin-attendance-detail-time-pair__input"
                                value="{{ old('clock_out', $clockOut) }}"
                                placeholder="18:00"
                                @if($isFutureDate) disabled @endif>
                        </div>
                    </div>

                    @foreach ($breakInputs as $index => $breakInput)
                    <div class="admin-attendance-detail-row">
                        <p class="admin-attendance-detail-row__label">休憩{{ $index + 1 }}</p>

                        <div class="admin-attendance-detail-time-pair">
                            <input
                                type="text"
                                name="breaks[{{ $index }}][break_start_at]"
                                class="admin-attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_start_at", $breakInput['break_start_at']) }}"
                                placeholder="12:00"
                                @if($isFutureDate) disabled @endif>
                            <span class="admin-attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="text"
                                name="breaks[{{ $index }}][break_end_at]"
                                class="admin-attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_end_at", $breakInput['break_end_at']) }}"
                                placeholder="13:00"
                                @if($isFutureDate) disabled @endif>
                        </div>
                    </div>
                    @endforeach

                    <div class="admin-attendance-detail-row admin-attendance-detail-row--textarea">
                        <p class="admin-attendance-detail-row__label">備考</p>

                        <textarea
                            name="note"
                            class="admin-attendance-detail-textarea"
                            @if($isFutureDate) disabled @endif>{{ old('note', $attendance->note) }}</textarea>
                    </div>
                </div>

                <div class="admin-attendance-detail-action">
                    <button
                        type="submit"
                        class="admin-attendance-detail-action__button"
                        @if($isFutureDate) disabled @endif>
                        修正
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>