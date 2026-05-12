<!DOCTYPE html>
<html lang="es" class="{{ Auth::user()->tema === 'claro' ? 'light' : 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally — @yield('title', 'Panel')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ── Tema oscuro (por defecto) ──────────────────────────────── */
        :root, html.dark {
            --bg-base:          #03060f;
            --bg-sidebar:       rgba(5, 10, 22, 0.97);
            --panel-bg:         rgba(8, 14, 30, 0.88);
            --border-subtle:    rgba(255, 255, 255, 0.07);
            --accent-gold:      #8b5cf6;
            --accent-teal:      #4dcfcf;
            --star-white:       #e8edf8;
            --text-dim:         rgba(180, 200, 240, 0.55);
            --text-muted:       rgba(180, 200, 240, 0.30);
            --btn-logout-color: rgba(180, 200, 240, 0.45);
            --stars-opacity:    1;
            --sidebar-shadow:   4px 0 24px rgba(0,0,0,0.4);
            --panel-shadow:     0 4px 24px rgba(0,0,0,0.3);
        }

        /* ── Tema claro ─────────────────────────────────────────────── */
        html.light {
            --bg-base:          #f5f3ff;
            --bg-sidebar:       rgba(250, 249, 255, 0.98);
            --panel-bg:         rgba(255, 255, 255, 0.98);
            --border-subtle:    rgba(109, 40, 217, 0.1);
            --accent-gold:      #6d28d9;
            --accent-teal:      #0a9494;
            --star-white:       #1e1b4b;
            --text-dim:         rgba(76, 29, 149, 0.55);
            --text-muted:       rgba(76, 29, 149, 0.32);
            --btn-logout-color: rgba(76, 29, 149, 0.45);
            --stars-opacity:    0;
            --sidebar-shadow:   4px 0 32px rgba(109, 40, 217, 0.09);
            --panel-shadow:     0 2px 16px rgba(109, 40, 217, 0.08);
        }

        html.light body {
            background: linear-gradient(160deg, #f5f3ff 0%, #fdf8ff 50%, #ede9fe 100%);
            background-attachment: fixed;
        }

        * { box-sizing: border-box; }

        body {
            background:  var(--bg-base);
            font-family: 'Jost', sans-serif;
            color:       var(--star-white);
            min-height:  100vh;
            transition:  background 0.3s, color 0.3s;
        }

        /* Estrellas (solo en modo oscuro) */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none;
            opacity: var(--stars-opacity);
            transition: opacity 0.4s;
            background-image:
                radial-gradient(1px   1px   at  8% 12%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px   1px   at 22% 38%, #ffffff44 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 38%  6%, #ffffff88 0%, transparent 100%),
                radial-gradient(1px   1px   at 52% 58%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px   1px   at 68% 18%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 83% 43%, #ffffff77 0%, transparent 100%),
                radial-gradient(1px   1px   at 14% 68%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px   1px   at 58% 83%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px   1px   at 92% 73%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 32% 52%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px   1px   at 76% 87%, #ffffff22 0%, transparent 100%),
                radial-gradient(1px   1px   at  4% 93%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px   1px   at 94%  4%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px   1px   at 48% 28%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px   1px   at 18% 48%, #ffffff33 0%, transparent 100%);
        }

        /* ── Sidebar ─────────────────────────────────────────────────── */
        .sidebar {
            position:        fixed;
            left: 0; top: 0; bottom: 0;
            width:           220px;
            background:      var(--bg-sidebar);
            border-right:    1px solid var(--border-subtle);
            box-shadow:      var(--sidebar-shadow);
            z-index:         50;
            display:         flex;
            flex-direction:  column;
            padding:         24px 0;
            backdrop-filter: blur(16px);
            transition:      background 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .sidebar-logo {
            font-family:    'Cinzel', serif;
            font-size:      20px;
            font-weight:    600;
            letter-spacing: 3px;
            background:     linear-gradient(135deg, #c4b5fd, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding:        0 24px 24px;
            border-bottom:  1px solid var(--border-subtle);
            transition:     border-color 0.3s;
        }
        .sidebar-logo span {
            display:                 block;
            font-family:             'Jost', sans-serif;
            font-size:               10px;
            font-weight:             300;
            letter-spacing:          5px;
            color:                   var(--text-dim);
            -webkit-text-fill-color: var(--text-dim);
            margin-top:              3px;
            transition:              color 0.3s;
        }
        html.light .sidebar-logo {
            background:     linear-gradient(135deg, #7c3aed, #4c1d95);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        nav.sidebar-nav {
            flex:    1;
            padding: 20px 0;
        }

        .nav-link {
            display:         flex;
            align-items:     center;
            gap:             11px;
            padding:         10px 24px;
            font-size:       13px;
            font-weight:     400;
            letter-spacing:  1px;
            color:           var(--text-dim);
            text-decoration: none;
            border-left:     2px solid transparent;
            transition:      color 0.2s, background 0.2s, border-color 0.2s;
        }
        .nav-link i {
            width:      15px;
            font-size:  13px;
            opacity:    0.8;
            flex-shrink: 0;
            text-align: center;
        }
        .nav-link:hover {
            color:             var(--star-white);
            background:        rgba(128, 128, 128, 0.05);
            border-left-color: rgba(139, 92, 246, 0.35);
        }
        .nav-link.active {
            color:             var(--accent-gold);
            background:        rgba(139, 92, 246, 0.06);
            border-left-color: var(--accent-gold);
        }

        .sidebar-user {
            padding:    16px 24px;
            border-top: 1px solid var(--border-subtle);
            font-size:  12px;
            transition: border-color 0.3s;
        }
        .sidebar-user .user-name {
            font-weight:   500;
            color:         var(--star-white);
            margin-bottom: 3px;
            font-size:     13px;
        }
        .sidebar-user .user-email {
            color:         var(--text-dim);
            font-size:     11px;
            margin-bottom: 12px;
            overflow:      hidden;
            text-overflow: ellipsis;
            white-space:   nowrap;
        }

        .btn-logout {
            display:        flex;
            align-items:    center;
            gap:            7px;
            font-size:      12px;
            color:          var(--btn-logout-color);
            background:     none;
            border:         none;
            cursor:         pointer;
            padding:        0;
            letter-spacing: 0.5px;
            font-family:    'Jost', sans-serif;
            transition:     color 0.2s;
        }
        .btn-logout:hover { color: #ff6644; }

        /* ── Main ──────────────────────────────────────────────────── */
        .main-content {
            margin-left: 220px;
            min-height:  100vh;
            padding:     32px;
            position:    relative;
            z-index:     1;
        }

        /* ── Panel ─────────────────────────────────────────────────── */
        .panel {
            background:      var(--panel-bg);
            border:          1px solid var(--border-subtle);
            border-radius:   4px;
            backdrop-filter: blur(8px);
            box-shadow:      var(--panel-shadow);
            transition:      background 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        /* ── Tabs ──────────────────────────────────────────────────── */
        .tabs-bar {
            display:       flex;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 28px;
            transition:    border-color 0.3s;
        }
        .tab-btn {
            padding:         10px 20px;
            font-family:     'Jost', sans-serif;
            font-size:       13px;
            font-weight:     400;
            letter-spacing:  0.5px;
            color:           var(--text-dim);
            background:      none;
            border:          none;
            border-bottom:   2px solid transparent;
            cursor:          pointer;
            text-decoration: none;
            display:         inline-block;
            margin-bottom:   -1px;
            transition:      color 0.2s, border-color 0.2s;
        }
        .tab-btn:hover  { color: var(--star-white); }
        .tab-btn.active { color: var(--accent-gold); border-bottom-color: var(--accent-gold); }

        /* ── Page header ────────────────────────────────────────────── */
        .page-header  { margin-bottom: 28px; }
        .page-title {
            font-family:    'Cinzel', serif;
            font-size:      22px;
            font-weight:    600;
            letter-spacing: 1.5px;
            color:          var(--star-white);
        }
        .page-subtitle {
            font-size:  13px;
            color:      var(--text-dim);
            margin-top: 4px;
        }

        /* ── Buttons ────────────────────────────────────────────────── */
        .btn-primary {
            display:         inline-flex;
            align-items:     center;
            gap:             8px;
            padding:         9px 20px;
            font-family:     'Jost', sans-serif;
            font-size:       13px;
            font-weight:     500;
            letter-spacing:  0.5px;
            color:           #ffffff;
            background:      linear-gradient(135deg, #8b5cf6, #4c1d95);
            border:          none;
            border-radius:   3px;
            cursor:          pointer;
            text-decoration: none;
            transition:      opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow:      0 3px 12px rgba(139,92,246,0.18);
        }
        .btn-primary:hover {
            opacity:    0.9;
            transform:  translateY(-1px);
            box-shadow: 0 6px 20px rgba(139,92,246,0.25);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            display:         inline-flex;
            align-items:     center;
            gap:             8px;
            padding:         9px 20px;
            font-family:     'Jost', sans-serif;
            font-size:       13px;
            font-weight:     400;
            letter-spacing:  0.5px;
            color:           var(--text-dim);
            background:      rgba(128, 128, 128, 0.05);
            border:          1px solid var(--border-subtle);
            border-radius:   3px;
            cursor:          pointer;
            text-decoration: none;
            transition:      background 0.2s, color 0.2s, border-color 0.2s;
        }
        .btn-secondary:hover {
            background:   rgba(128, 128, 128, 0.1);
            color:        var(--star-white);
            border-color: rgba(128, 128, 128, 0.15);
        }

        /* ── Form ──────────────────────────────────────────────────── */
        .form-input {
            width:         100%;
            background:    rgba(128, 128, 128, 0.05);
            border:        1px solid var(--border-subtle);
            border-radius: 3px;
            padding:       10px 14px;
            font-family:   'Jost', sans-serif;
            font-size:     14px;
            color:         var(--star-white);
            outline:       none;
            transition:    border-color 0.2s, background 0.2s;
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-input:focus        { border-color: rgba(139, 92, 246, 0.5); background: rgba(128,128,128,0.07); }

        .form-label {
            display:        block;
            font-size:      11px;
            font-weight:    500;
            letter-spacing: 1.5px;
            color:          var(--text-dim);
            margin-bottom:  6px;
            text-transform: uppercase;
        }

        /* Modo claro: inputs */
        html.light .form-input {
            background:   rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.1);
        }
        html.light .form-input:focus {
            border-color: var(--accent-gold);
            background:   rgba(0, 0, 0, 0.06);
        }

        /* select en modo oscuro */
        html.dark .form-input option, .form-input option {
            background: #0d1526;
            color: #e8edf8;
        }
        html.light .form-input option {
            background: #ffffff;
            color: #111827;
        }

        /* ── Alerts ────────────────────────────────────────────────── */
        .alert-success {
            padding:       12px 16px;
            background:    rgba(77, 207, 207, 0.08);
            border:        1px solid rgba(77, 207, 207, 0.25);
            border-radius: 3px;
            font-size:     13px;
            color:         var(--accent-teal);
            margin-bottom: 20px;
        }
        .alert-error {
            padding:       12px 16px;
            background:    rgba(255, 100, 80, 0.08);
            border:        1px solid rgba(255, 100, 80, 0.25);
            border-radius: 3px;
            font-size:     13px;
            color:         #ff8866;
            margin-bottom: 20px;
        }

        /* ── Modal ─────────────────────────────────────────────────── */
        .modal-overlay {
            position:        fixed;
            inset:           0;
            z-index:         100;
            background:      rgba(3, 6, 15, 0.82);
            backdrop-filter: blur(5px);
            display:         flex;
            align-items:     center;
            justify-content: center;
        }
        html.light .modal-overlay {
            background: rgba(180, 190, 210, 0.72);
        }
        .modal-box {
            background:    var(--panel-bg);
            border:        1px solid rgba(139, 92, 246, 0.2);
            border-radius: 4px;
            width:         100%;
            max-width:     480px;
            padding:       32px;
            box-shadow:    0 24px 64px rgba(0,0,0,0.5);
        }
        .modal-title {
            font-family:    'Cinzel', serif;
            font-size:      18px;
            font-weight:    600;
            color:          var(--star-white);
            margin-bottom:  24px;
            padding-bottom: 12px;
            border-bottom:  1px solid var(--border-subtle);
        }
    </style>

    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            Orbitally
            <span>Gestor de Productividad</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                Dashboard
            </a>
            <?php // todo -> Corregir el icono ?>
            <a href="{{ route('tasks.index') }}"
               class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="fa-regular fa-rectangle-list"></i>
                Tareas
            </a>
            <a href="{{ route('calendar') }}"
               class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <i class="fa-regular fa-calendar-days"></i>
                Calendario
            </a>
            <a href="{{ route('categories.index') }}"
               class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fa-regular fa-folder-open"></i>
                Categorías
            </a>
            <a href="{{ route('pomodoro.index') }}"
                class="nav-link {{ request()->routeIs('pomodoro.*') ? 'active' : '' }}">
                <i class="fa-solid fa-stopwatch"></i>
                Pomodoro
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-name">{{ Auth::user()->nombre }}</div>
            <div class="user-email">{{ Auth::user()->email }}</div>

            {{-- Toggle de tema como fila completa --}}
            <form method="POST" action="{{ route('tema.toggle') }}" style="margin-bottom:10px;">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%; justify-content:space-between; padding:6px 0;"
                        title="{{ Auth::user()->tema === 'oscuro' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro' }}">
                    <span style="display:flex; align-items:center; gap:7px;">
                        @if(Auth::user()->tema === 'oscuro')
                            <i class="fa-regular fa-sun" style="width:15px; text-align:center;"></i>
                            Modo claro
                        @else
                            <i class="fa-regular fa-moon" style="width:15px; text-align:center;"></i>
                            Modo oscuro
                        @endif
                    </span>
                    <?php // todo -> Corregir el modo claro/oscuro ?>
                    <span style="font-size:10px; opacity:0.5;">
                        {{ Auth::user()->tema === 'oscuro' ? '☀' : '☾' }}
                    </span>
                </button>
            </form>
        
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%;">
                    <i class="fa-solid fa-power-off" style="width:15px; text-align:center;"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>