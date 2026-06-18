<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            background: #f9fafb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #1f2937;
            text-align: center;
            padding: 2rem;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .error-container {
            max-width: 400px;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1rem;
            color: #065f46;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .error-message {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .error-image {
            max-width: 200px;
            margin-bottom: 1.5rem;
        }

        .home-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #065f46;
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .home-button:hover {
            background-color: #047857;
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.25rem;
            }
            
            .error-image {
                max-width: 500px;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <img src="@yield('image')" alt="" class="error-image" loading="lazy">
        <div class="error-code">@yield('code')</div>
        <h2 class="error-title">@yield('message-title')</h2>
        <p class="error-message">@yield('message-body')</p>
        <a href="{{ url('/') }}" class="home-button">Kembali ke Beranda</a>
    </div>
</body>

</html>