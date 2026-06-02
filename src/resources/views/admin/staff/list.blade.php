<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ一覧</title>
    <link rel="stylesheet" href="{{ asset('css/admin-staff-list.css') }}">
</head>

<body>
    <header class="admin-staff-list-header">
        <div class="admin-staff-list-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="admin-staff-list-header__logo">

            <nav class="admin-staff-list-header__nav">
                <a href="/admin/attendance/list" class="admin-staff-list-header__link">勤怠一覧</a>
                <a href="/admin/staff/list" class="admin-staff-list-header__link">スタッフ一覧</a>
                <a href="#" class="admin-staff-list-header__link">申請一覧</a>

                <form method="POST" action="{{ route('logout') }}" class="admin-staff-list-header__logout-form">
                    @csrf
                    <button type="submit" class="admin-staff-list-header__logout">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="admin-staff-list-main">
        <section class="admin-staff-list-content">
            <h1 class="admin-staff-list-content__title">
                <span class="admin-staff-list-content__title-bar"></span>
                <span class="admin-staff-list-content__title-text">スタッフ一覧</span>
            </h1>
            <table class="admin-staff-list-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        {{-- ここから修正：見出しを「月次勤怠」に変更 --}}
                        <th>月次勤怠</th>
                        {{-- ここまで修正：見出しを「月次勤怠」に変更 --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffs as $staff)
                    <tr>
                        <td>{{ $staff->name }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>
                            <a href="{{ route('admin.staff.attendance', ['id' => $staff->id]) }}" class="admin-staff-list-table__detail-link">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>