<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請一覧</title>
    <link rel="stylesheet" href="{{ asset('css/admin-stamp-correction-request-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-header.css') }}">
</head>

<body>
    @include('components.admin-header')

    <main class="admin-request-list-main">
        <section class="admin-request-list-content">
            <h1 class="admin-request-list-content__title">
                <span class="admin-request-list-content__title-bar"></span>
                <span class="admin-request-list-content__title-text">申請一覧</span>
            </h1>

            <div class="admin-request-list-tabs">
                <a
                    href="{{ route('admin.stamp_correction_request.list', ['status' => 'pending']) }}"
                    class="admin-request-list-tabs__link {{ $status === 'pending' ? 'admin-request-list-tabs__link--active' : '' }}">
                    承認待ち
                </a>

                <a
                    href="{{ route('admin.stamp_correction_request.list', ['status' => 'approved']) }}"
                    class="admin-request-list-tabs__link {{ $status === 'approved' ? 'admin-request-list-tabs__link--active' : '' }}">
                    承認済み
                </a>
            </div>

            <table class="admin-request-list-table">
                <thead>
                    <tr>
                        <th>状態</th>
                        <th>名前</th>
                        <th>対象日時</th>
                        <th>申請理由</th>
                        <th>申請日時</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $requestItem)
                    <tr>
                        <td>{{ $requestItem['status'] }}</td>
                        <td>{{ $requestItem['user_name'] }}</td>
                        <td>{{ $requestItem['work_date'] }}</td>
                        <td>{{ $requestItem['note'] }}</td>
                        <td>{{ $requestItem['request_date'] }}</td>
                        <td>
                            <a
                                href="{{ route('admin.stamp_correction_request.detail', ['id' => $requestItem['id']]) }}"
                                class="admin-request-list-table__detail-link">
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