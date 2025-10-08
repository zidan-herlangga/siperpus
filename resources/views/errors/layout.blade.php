<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #f9fafb, #e5e7eb);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #1f2937;
            text-align: center;
            padding: 1rem;
        }

        h1 {
            font-size: 7rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 1rem;
            font-weight: 600;
        }

        p {
            margin-top: 0.75rem;
            color: #6b7280;
            max-width: 420px;
        }

        a {
            margin-top: 2rem;
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #2563eb;
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        a:hover {
            background-color: #1e40af;
        }

        img {
            max-width: 300px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body style="@yield('body-style')">
    <img src="@yield('image')" alt="Error Image">
    <h1 style="color:@yield('color')">@yield('code')</h1>
    <h2>@yield('message-title')</h2>
    <p>@yield('message-body')</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>

</html>
