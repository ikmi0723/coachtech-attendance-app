<header class="shared-header">
    <div class="shared-header__inner">
        <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="shared-header__logo">

        <nav class="shared-header__nav">
            <a
                href="{{ route('admin.attendance.list') }}"
                class="shared-header__link {{ request()->routeIs('admin.attendance.list', 'admin.attendance.detail', 'admin.attendance.update', 'admin.staff.attendance') ? 'shared-header__link--active' : '' }}">
                勤怠一覧
            </a>

            <a
                href="{{ route('admin.staff.list') }}"
                class="shared-header__link {{ request()->routeIs('admin.staff.list') ? 'shared-header__link--active' : '' }}">
                スタッフ一覧
            </a>

            <a
                href="{{ route('admin.stamp_correction_request.list') }}"
                class="shared-header__link {{ request()->routeIs('admin.stamp_correction_request.list', 'admin.stamp_correction_request.detail', 'admin.stamp_correction_request.approve') ? 'shared-header__link--active' : '' }}">
                申請一覧
            </a>

            <form method="POST" action="{{ route('logout') }}" class="shared-header__logout-form">
                @csrf
                <button type="submit" class="shared-header__logout">ログアウト</button>
            </form>
        </nav>
    </div>
</header>