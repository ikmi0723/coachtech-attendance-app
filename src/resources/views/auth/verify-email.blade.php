<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <main class="auth-main">
        <section class="auth-card">
            <h1 class="auth-card__title">メール認証</h1>

            @if (session('status') === 'verification-link-sent')
            <p class="auth-card__message auth-card__message--success">
                認証メールを再送しました。
            </p>
            @endif

            <p class="auth-card__text">
                登録していただいたメールアドレスに認証メールを送信しました。<br>
                メール内の認証リンクをクリックして、メール認証を完了してください。
            </p>

            <p class="auth-card__text">
                認証後、サービスをご利用いただけます。
            </p>

            <form method="POST" action="{{ url('/email/verification-notification') }}" class="auth-card__form">
                @csrf
                <button type="submit" class="auth-card__button">
                    認証メールを再送する
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="auth-card__form auth-card__form--logout">
                @csrf
                <button type="submit" class="auth-card__link-button">
                    ログアウト
                </button>
            </form>
        </section>
    </main>
</body>

</html>