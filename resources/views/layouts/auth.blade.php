<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally · @yield('title', 'Acceso')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --space-deep:    #03060f;
            --accent-gold:   #8b5cf6;
            --star-white:    #e8edf8;
            --text-dim:      rgba(180, 200, 240, 0.55);
            --border-subtle: rgba(255, 255, 255, 0.07);
            --panel-bg:      rgba(5, 10, 22, 0.92);
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

        /* Starfield */
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
                radial-gradient(1px 1px at 48% 28%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px 1px at 33% 85%, #ffffff33 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 62% 5%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 92%, #ffffff22 0%, transparent 100%);
            pointer-events: none;
        }

        /* Subtle glow under card */
        body::after {
            content: '';
            position: fixed;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 600px; height: 300px;
            background: radial-gradient(ellipse at center bottom, rgba(139,92,246,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .auth-card {
            position: relative; z-index: 1;
            background: var(--panel-bg);
            border: 1px solid rgba(139, 92, 246, 0.18);
            border-radius: 4px;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            backdrop-filter: blur(16px);
            box-shadow: 0 32px 64px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03) inset;
        }

        .auth-logo {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.15em;
            background: linear-gradient(135deg, #c4b5fd, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 4px;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 11px;
            font-weight: 300;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 28px;
        }

        .auth-separator {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(139,92,246,0.35), transparent);
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 7px;
        }

        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 2px;
            padding: 11px 14px;
            font-family: 'Jost', sans-serif;
            font-size: 14px;
            color: var(--star-white);
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .form-input::placeholder { color: rgba(180, 200, 240, 0.22); }
        .form-input:focus {
            border-color: rgba(139, 92, 246, 0.5);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #ffffff;
            background: linear-gradient(135deg, #8b5cf6, #4c1d95);
            border: none;
            border-radius: 2px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 8px;
            box-shadow: 0 4px 16px rgba(139, 92, 246, 0.2);
        }
        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(139, 92, 246, 0.28);
        }
        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-dim);
        }
        .auth-footer a {
            color: var(--accent-gold);
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .auth-footer a:hover { opacity: 0.75; }

        .alert-error {
            padding: 11px 14px;
            background: rgba(255, 100, 80, 0.08);
            border: 1px solid rgba(255, 100, 80, 0.22);
            border-radius: 2px;
            font-size: 13px;
            color: #ff8866;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .back-link {
            position: fixed;
            top: 24px;
            left: 24px;
            font-size: 12px;
            color: rgba(180, 200, 240, 0.45);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.06em;
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