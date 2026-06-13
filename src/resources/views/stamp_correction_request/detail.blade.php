<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請詳細</title>
    <link rel="stylesheet" href="{{ asset('css/stamp-correction-request-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.user-header')

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