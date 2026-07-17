<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error')</title>

    <style>
        :root {
            --bg-color: #f3f4f6;
            --text-main: #1f2937;
            --text-muted: #4b5563;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --card-bg: #ffffff;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #0f172a;
                --text-main: #f8fafc;
                --text-muted: #94a3b8;
                --accent: #3b82f6;
                --accent-hover: #60a5fa;
                --card-bg: #1e293b;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .error-container {
            text-align: center;
            max-width: 28rem;
            width: 100%;
            padding: 2.5rem;
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .error-message {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 1rem;
        }

        .error-description {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .btn-home {
            display: inline-block;
            background-color: var(--accent);
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .btn-home:hover {
            background-color: var(--accent-hover);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">
            @yield('code')
        </div>

        <div class="error-message">
            @yield('message')
        </div>

        <div class="error-description">
            @yield('description', 'Lo sentimos, ha ocurrido un error inesperado.')
        </div>

        <a href="{{ url('/') }}" class="btn-home">
            Volver al inicio
        </a>
    </div>
</body>
</html>
