<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠詳細</title>
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.user-header')

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

            <form method="POST" action="{{ route('attendance.detail.update', ['id' => $attendance?->id ?? 0, 'date' => $detailDate]) }}">
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
                                type="text"
                                name="clock_in"
                                class="attendance-detail-time-pair__input"
                                value="{{ old('clock_in', $clockIn) }}"
                                placeholder="09:00"
                                @if($isFutureDate || $hasPendingRequest) disabled @endif>
                            <span class="attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="text"
                                name="clock_out"
                                class="attendance-detail-time-pair__input"
                                value="{{ old('clock_out', $clockOut) }}"
                                placeholder="18:00"
                                @if($isFutureDate || $hasPendingRequest) disabled @endif>
                        </div>
                    </div>

                    @foreach ($breakInputs as $index => $breakInput)
                    <div class="attendance-detail-row">
                        <p class="attendance-detail-row__label">休憩{{ $index + 1 }}</p>

                        <div class="attendance-detail-time-pair">
                            <input
                                type="text"
                                name="breaks[{{ $index }}][break_start_at]"
                                class="attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_start_at", $breakInput['break_start_at']) }}"
                                placeholder="12:00"
                                @if($isFutureDate || $hasPendingRequest) disabled @endif>
                            <span class="attendance-detail-time-pair__separator">〜</span>
                            <input
                                type="text"
                                name="breaks[{{ $index }}][break_end_at]"
                                class="attendance-detail-time-pair__input"
                                value="{{ old("breaks.$index.break_end_at", $breakInput['break_end_at']) }}"
                                placeholder="13:00"
                                @if($isFutureDate || $hasPendingRequest) disabled @endif>
                        </div>
                    </div>
                    @endforeach

                    <div class="attendance-detail-row attendance-detail-row--textarea">
                        <p class="attendance-detail-row__label">備考</p>

                        <textarea
                            name="note"
                            class="attendance-detail-textarea"
                            @if($isFutureDate || $hasPendingRequest) disabled @endif>{{ old('note', $attendance->note ?? '') }}</textarea>
                    </div>
                </div>

                @if ($hasPendingRequest)
                <p class="attendance-detail-pending-message">* 承認待ちのため修正はできません。</p>
                @elseif (!$isFutureDate)
                <div class="attendance-detail-action">
                    <button
                        type="submit"
                        class="attendance-detail-action__button">
                        修正
                    </button>
                </div>
                @endif
            </form>
        </section>
    </main>
</body>

</html>