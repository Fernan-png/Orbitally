<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally — @yield('title', 'Acceso')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --space-deep: #03060f;
            --accent-gold: #c9a84c;
            --star-white: #e8edf8;
            --text-dim: rgba(180, 200, 240, 0.55);
            --border-subtle: rgba(255,255,255,0.07);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--space-deep);
            font-family: 'Jost', sans-serif;
            color: var(--star-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background-image:
                radial-gradient(1px 1px at 10% 15%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 40%, #ffffff33 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 40% 8%, #ffffff77 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 60%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 20%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 45%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 70%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 75%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 48% 28%, #ffffff55 0%, transparent 100%);
            pointer-events: none;
        }

        .auth-card {
            position: relative; z-index: 1;
            background: rgba(5, 10, 22, 0.92);
            border: 1px solid rgba(201,168,76,0.15);
            border-radius: 4px;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            backdrop-filter: blur(12px);
        }

        .auth-logo {
            font-family: 'Cinzel', serif;
            font-size: 1.6rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            background: linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 0.72rem;
            font-weight: 300;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 2rem;
        }

        .auth-separator {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(201,168,76,0.3), transparent);
            margin-bottom: 2rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 0.4rem;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 2px;
            padding: 0.65rem 0.9rem;
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            color: var(--star-white);
            outline: none;
            transition: border-color 0.2s;
        }
        .form-input::placeholder { color: rgba(180,200,240,0.25); }
        .form-input:focus { border-color: rgba(201,168,76,0.45); }

        .form-error {
            font-size: 0.72rem;
            color: #ff8866;
            margin-top: 0.3rem;
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #03060f;
            background: linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            border: none;
            border-radius: 2px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
            margin-top: 0.5rem;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.78rem;
            color: var(--text-dim);
        }
        .auth-footer a {
            color: var(--accent-gold);
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .auth-footer a:hover { opacity: 0.8; }

        .alert-error {
            padding: 0.7rem 1rem;
            background: rgba(255,100,80,0.1);
            border: 1px solid rgba(255,100,80,0.25);
            border-radius: 2px;
            font-size: 0.8rem;
            color: #ff8866;
            margin-bottom: 1.25rem;
        }

        .back-link {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 0.75rem;
            color: var(--text-dim);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.05em;
            transition: color 0.2s;
            z-index: 10;
        }
        .back-link:hover { color: var(--star-white); }
    </style>
</head>
<body>
    <a href="{{ route('welcome') }}" class="back-link">← Inicio</a>
    <div class="auth-card">
        @yield('content')
    </div>
</body>
</html>
