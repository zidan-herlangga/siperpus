<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f6f1">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-color: #f4f6f1;
            background-image: radial-gradient(#e2e9e1 1px, transparent 1px);
            background-size: 24px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #16271d;
            text-align: center;
            padding: 2rem;
            font-family: "Manrope Variable", system-ui, -apple-system, sans-serif;
        }

        .error-container {
            max-width: 420px;
        }

        .error-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #5e6e63;
            margin-bottom: 1.5rem;
        }

        .error-code {
            font-size: clamp(4rem, 16vw, 7rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1rem;
            color: #006739;
            letter-spacing: -0.03em;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #16271d;
            letter-spacing: -0.01em;
        }

        .error-message {
            color: #5e6e63;
            margin-bottom: 2.25rem;
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .home-button {
            display: inline-block;
            padding: 0.75rem 1.75rem;
            background-color: #006739;
            color: white;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: background-color 0.2s ease;
        }

        .home-button:hover {
            background-color: #004225;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-eyebrow">Perpustakaan Digital SMK Karya Guna 2</div>
        <div class="error-code">@yield('code')</div>
        <h2 class="error-title">@yield('message-title')</h2>
        <p class="error-message">@yield('message-body')</p>
        <a href="{{ url('/') }}" class="home-button">Kembali ke Beranda</a>
    </div>
</body>

</html>