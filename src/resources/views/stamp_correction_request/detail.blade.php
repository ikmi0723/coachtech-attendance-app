<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請詳細</title>
    <link rel="stylesheet" href="{{ asset('css/stamp-correction-request-detail.css') }}">
</head>

<body>
    <header class="stamp-request-detail-header">
        <div class="stamp-request-detail-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="stamp-request-detail-header__logo">

            <nav class="stamp-request-detail-header__nav">
                <a href="/attendance" class="stamp-request-detail-header__link">勤怠</a>
                <a href="/attendance/list" class="stamp-request-detail-header__link">勤怠一覧</a>
                <a href="/stamp_correction_request/list" class="stamp-request-detail-header__link">申請</a>

                <form method="POST" action="{{ route('logout') }}" class="stamp-request-detail-header__logout-form">
                    @csrf
                    <button type="submit" class="stamp-request-detail-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="stamp-request-detail-main">
        <section class="stamp-request-detail-content">
            <h1 class="stamp-request-detail-content__title">申請詳細</h1>

            <div class="stamp-request-detail-status">{{ $statusLabel }}</div>

            <div class="stamp-request-detail-card">
                <div class="stamp-request-detail-row">
                    <p class="stamp-request-detail-row__label">日付</p>
                    <p class="stamp-request-detail-row__value">{{ $displayDate }}</p>
                </div>

                <div class="stamp-request-detail-row">
                    <p class="stamp-request-detail-row__label">出勤・退勤</p>
                    <p class="stamp-request-detail-row__value">{{ $clockIn }} 〜 {{ $clockOut }}</p>
                </div>

                @foreach ($breakInputs as $index => $breakInput)
                <div class="stamp-request-detail-row">
                    <p class="stamp-request-detail-row__label">休憩{{ $index + 1 }}</p>
                    <p class="stamp-request-detail-row__value">
                        {{ $breakInput['break_start_at'] }} 〜 {{ $breakInput['break_end_at'] }}
                    </p>
                </div>
                @endforeach

                <div class="stamp-request-detail-row">
                    <p class="stamp-request-detail-row__label">備考</p>
                    <p class="stamp-request-detail-row__value">{{ $correctionRequest->requested_note }}</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>