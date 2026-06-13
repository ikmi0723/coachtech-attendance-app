<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請詳細</title>
    <link rel="stylesheet" href="{{ asset('css/admin-stamp-correction-request-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.admin-header')

    <main class="admin-request-detail-main">
        <section class="admin-request-detail-content">
            <h1 class="admin-request-detail-content__title">
                <span class="admin-request-detail-content__title-bar"></span>
                <span class="admin-request-detail-content__title-text">申請詳細</span>
            </h1>

            @if (session('message'))
            <p class="admin-request-detail-alert admin-request-detail-alert--success">{{ session('message') }}</p>
            @endif

            <div class="admin-request-detail-card">
                <div class="admin-request-detail-row">
                    <p class="admin-request-detail-row__label">名前</p>
                    <p class="admin-request-detail-row__value">{{ $userName }}</p>
                </div>

                <div class="admin-request-detail-row">
                    <p class="admin-request-detail-row__label">日付</p>
                    <p class="admin-request-detail-row__value">{{ $displayDate }}</p>
                </div>

                <div class="admin-request-detail-row">
                    <p class="admin-request-detail-row__label">出勤・退勤</p>
                    <div class="admin-request-detail-time-pair">
                        <p class="admin-request-detail-time-pair__value">{{ $clockIn }}</p>
                        <span class="admin-request-detail-time-pair__separator">〜</span>
                        <p class="admin-request-detail-time-pair__value">{{ $clockOut }}</p>
                    </div>
                </div>

                @foreach ($breakInputs as $index => $breakInput)
                <div class="admin-request-detail-row">
                    <p class="admin-request-detail-row__label">休憩{{ $index + 1 }}</p>
                    <div class="admin-request-detail-time-pair">
                        <p class="admin-request-detail-time-pair__value">{{ $breakInput['break_start_at'] }}</p>
                        <span class="admin-request-detail-time-pair__separator">〜</span>
                        <p class="admin-request-detail-time-pair__value">{{ $breakInput['break_end_at'] }}</p>
                    </div>
                </div>
                @endforeach

                <div class="admin-request-detail-row admin-request-detail-row--textarea">
                    <p class="admin-request-detail-row__label">備考</p>
                    <p class="admin-request-detail-row__value">{{ $correctionRequest->requested_note }}</p>
                </div>
            </div>

            @if ($isApproved)
            <div class="admin-request-detail-action admin-request-detail-action--approved">
                <span class="admin-request-detail-action__approved-label">承認済み</span>
            </div>
            @else
            <form method="POST" action="{{ route('admin.stamp_correction_request.approve', ['id' => $correctionRequest->id]) }}">
                @csrf
                <div class="admin-request-detail-action">
                    <button type="submit" class="admin-request-detail-action__button">承認</button>
                </div>
            </form>
            @endif
        </section>
    </main>
</body>

</html>