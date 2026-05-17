<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <header class="auth-header">
        <div class="auth-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="auth-header__logo">
        </div>
    </header>

    <main class="auth-main">
        <div class="auth-card">
            <h1 class="auth-card__title">管理者ログイン</h1>

            <form method="POST" action="{{ url('/admin/login') }}" class="auth-form" novalidate>
                @csrf

                <div class="auth-form__group">
                    <label for="email" class="auth-form__label">メールアドレス</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="auth-form__input">
                    @error('email')
                    <p class="auth-form__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-form__group">
                    <label for="password" class="auth-form__label">パスワード</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="auth-form__input">
                    @error('password')
                    <p class="auth-form__error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth-form__button">管理者ログインする</button>
            </form>
        </div>
    </main>
</body>

</html>