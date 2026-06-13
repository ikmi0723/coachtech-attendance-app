<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請一覧</title>
    <link rel="stylesheet" href="{{ asset('css/stamp-correction-request-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.user-header')

    <main class="stamp-request-main">
        <section class="stamp-request-content">
            <h1 class="stamp-request-content__title">申請一覧</h1>

            <div class="stamp-request-tabs">
                <a
                    href="{{ route('stamp_correction_request.list', ['status' => 'pending']) }}"
                    class="stamp-request-tabs__link {{ $status === 'pending' ? 'stamp-request-tabs__link--active' : '' }}">
                    承認待ち
                </a>

                <a
                    href="{{ route('stamp_correction_request.list', ['status' => 'approved']) }}"
                    class="stamp-request-tabs__link {{ $status === 'approved' ? 'stamp-request-tabs__link--active' : '' }}">
                    承認済み
                </a>
            </div>

            <table class="stamp-request-table">
                <thead>
                    <tr>
                        <th>状態</th>
                        <th>名前</th>
                        <th>対象日</th>
                        <th>申請理由</th>
                        <th>申請日時</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $requestItem)
                    <tr>
                        <td>{{ $requestItem['status'] }}</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td>{{ $requestItem['work_date'] }}</td>
                        <td>{{ $requestItem['note'] }}</td>
                        <td>{{ $requestItem['request_date'] }}</td>
                        <td>
                            <a
                                href="{{ route('stamp_correction_request.detail', ['id' => $requestItem['id']]) }}"
                                class="stamp-request-table__detail-link">
                                詳細
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">申請はありません</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>