<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <header class="auth-header">
        <div class="auth-header__inner">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="auth-header__logo">
        </div>
    </header>
    <main class="verify-main">
        <section class="verify-card">
            <p class="verify-card__text">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>

            <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" class="verify-card__mailhog-button">
                認証はこちらから
            </a>

            @if (session('status') === 'verification-link-sent')
            <p class="verify-card__status">認証メールを再送しました。</p>
            @endif

            <form method="POST" action="{{ url('/email/verification-notification') }}" class="verify-card__resend-form">
                @csrf
                <button type="submit" class="verify-card__resend-button">認証メールを再送する</button>
            </form>
        </section>
    </main>
</body>

</html>