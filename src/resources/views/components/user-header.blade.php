<header class="shared-header">
    <div class="shared-header__inner">
        <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="shared-header__logo">

        <nav class="shared-header__nav">
            <a
                href="{{ route('attendance.index') }}"
                class="shared-header__link {{ request()->routeIs('attendance.index') ? 'shared-header__link--active' : '' }}">
                勤怠
            </a>

            <a
                href="{{ route('attendance.list') }}"
                class="shared-header__link {{ request()->routeIs('attendance.list', 'attendance.detail', 'attendance.detail.update') ? 'shared-header__link--active' : '' }}">
                勤怠一覧
            </a>

            <a
                href="{{ route('stamp_correction_request.list') }}"
                class="shared-header__link {{ request()->routeIs('stamp_correction_request.list', 'stamp_correction_request.detail') ? 'shared-header__link--active' : '' }}">
                申請
            </a>

            <form method="POST" action="{{ route('logout') }}" class="shared-header__logout-form">
                @csrf
                <button type="submit" class="shared-header__logout">ログアウト</button>
            </form>
        </nav>
    </div>
</header>