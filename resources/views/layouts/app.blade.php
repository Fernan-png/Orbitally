<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally — @yield('title', 'Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --space-deep: #03060f;
            --space-mid: #080f20;
            --panel-bg: rgba(8, 14, 30, 0.85);
            --border-subtle: rgba(255,255,255,0.07);
            --accent-gold: #c9a84c;
            --accent-teal: #4dcfcf;
            --star-white: #e8edf8;
            --text-dim: rgba(180, 200, 240, 0.55);
        }

        * { box-sizing: border-box; }

        body {
            background: var(--space-deep);
            font-family: 'Jost', sans-serif;
            color: var(--star-white);
            min-height: 100vh;
        }

        /* Star background */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background-image:
                radial-gradient(1px 1px at 8% 12%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px 1px at 22% 38%, #ffffff44 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 38% 6%, #ffffff88 0%, transparent 100%),
                radial-gradient(1px 1px at 52% 58%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px 1px at 68% 18%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 83% 43%, #ffffff77 0%, transparent 100%),
                radial-gradient(1px 1px at 14% 68%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 58% 83%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px 1px at 92% 73%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 32% 52%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px 1px at 76% 87%, #ffffff22 0%, transparent 100%),
                radial-gradient(1px 1px at 4% 93%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 94% 4%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px 1px at 48% 28%, #ffffff66 0%, transparent 100%),
                radial-gradient(1px 1px at 18% 48%, #ffffff33 0%, transparent 100%);
            pointer-events: none;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 220px;
            background: rgba(5, 10, 22, 0.95);
            border-right: 1px solid var(--border-subtle);
            z-index: 50;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
            backdrop-filter: blur(12px);
        }

        .sidebar-logo {
            font-family: 'Cinzel', serif;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            background: linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .sidebar-logo span {
            display: block;
            font-family: 'Jost', sans-serif;
            font-size: 0.65rem;
            font-weight: 300;
            letter-spacing: 0.3em;
            color: var(--text-dim);
            -webkit-text-fill-color: var(--text-dim);
            margin-top: 2px;
        }

        nav.sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            font-size: 0.82rem;
            font-weight: 400;
            letter-spacing: 0.05em;
            color: rgba(180, 200, 240, 0.6);
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
            border-left: 2px solid transparent;
        }

        .nav-link:hover {
            color: var(--star-white);
            background: rgba(255,255,255,0.04);
            border-left-color: rgba(201, 168, 76, 0.4);
        }

        .nav-link.active {
            color: var(--accent-gold);
            background: rgba(201, 168, 76, 0.06);
            border-left-color: var(--accent-gold);
        }

        .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }

        .sidebar-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-subtle);
            font-size: 0.78rem;
        }

        .sidebar-user .user-name {
            font-weight: 500;
            color: var(--star-white);
            margin-bottom: 0.2rem;
        }

        .sidebar-user .user-email {
            color: var(--text-dim);
            font-size: 0.72rem;
            margin-bottom: 0.75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: rgba(180, 200, 240, 0.5);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }
        .btn-logout:hover { color: #ff7755; }

        /* Main content area */
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Panel cards */
        .panel {
            background: var(--panel-bg);
            border: 1px solid var(--border-subtle);
            border-radius: 4px;
            backdrop-filter: blur(8px);
        }

        /* Tabs */
        .tabs-bar {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 2rem;
        }

        .tab-btn {
            padding: 0.65rem 1.4rem;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.08em;
            color: var(--text-dim);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: color 0.2s, border-color 0.2s;
            margin-bottom: -1px;
            text-decoration: none;
            display: inline-block;
        }

        .tab-btn:hover { color: var(--star-white); }
        .tab-btn.active { color: var(--accent-gold); border-bottom-color: var(--accent-gold); }

        /* Page header */
        .page-header { margin-bottom: 2rem; }
        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            color: var(--star-white);
        }
        .page-subtitle {
            font-size: 0.8rem;
            color: var(--text-dim);
            margin-top: 0.25rem;
        }

        /* Buttons */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            color: #03060f;
            background: linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            border: none;
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.05em;
            color: rgba(180, 200, 240, 0.7);
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-subtle);
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.08); color: var(--star-white); }

        /* Form inputs */
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 2px;
            padding: 0.6rem 0.9rem;
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            color: var(--star-white);
            transition: border-color 0.2s;
            outline: none;
        }
        .form-input::placeholder { color: rgba(180,200,240,0.3); }
        .form-input:focus { border-color: rgba(201,168,76,0.5); }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--text-dim);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
        }

        /* Alert / flash */
        .alert-success {
            padding: 0.75rem 1rem;
            background: rgba(77,207,207,0.1);
            border: 1px solid rgba(77,207,207,0.3);
            border-radius: 2px;
            font-size: 0.82rem;
            color: var(--accent-teal);
            margin-bottom: 1.5rem;
        }
        .alert-error {
            padding: 0.75rem 1rem;
            background: rgba(255,100,80,0.1);
            border: 1px solid rgba(255,100,80,0.3);
            border-radius: 2px;
            font-size: 0.82rem;
            color: #ff8866;
            margin-bottom: 1.5rem;
        }

        /* Priority badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 2px;
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .badge-alta { background: rgba(255,100,80,0.15); color: #ff8866; border: 1px solid rgba(255,100,80,0.25); }
        .badge-media { background: rgba(255,180,50,0.15); color: #ffcc55; border: 1px solid rgba(255,180,50,0.25); }
        .badge-baja { background: rgba(77,207,207,0.12); color: var(--accent-teal); border: 1px solid rgba(77,207,207,0.25); }
        .badge-pendiente { background: rgba(255,180,50,0.1); color: #ffcc55; border: 1px solid rgba(255,180,50,0.2); }
        .badge-en_progreso { background: rgba(100,150,255,0.12); color: #88aaff; border: 1px solid rgba(100,150,255,0.25); }
        .badge-completada { background: rgba(77,207,207,0.1); color: var(--accent-teal); border: 1px solid rgba(77,207,207,0.2); }

        /* Modal overlay */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 100;
            background: rgba(3,6,15,0.8);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-box {
            background: #070e20;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 4px;
            width: 100%;
            max-width: 480px;
            padding: 2rem;
        }
        .modal-title {
            font-family: 'Cinzel', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--star-white);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-subtle);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            Orbitally
            <span>Gestor de Productividad</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm.75 3a.75.75 0 0 0-1.5 0V8c0 .2.08.39.22.53l2 2a.75.75 0 1 0 1.06-1.06L8.75 7.69V5.5z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2.5A.5.5 0 0 1 2.5 2h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 2.5zm0 4A.5.5 0 0 1 2.5 6h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 6.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/></svg>
                Tareas
            </a>
            <a href="{{ route('calendar') }}" class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <svg viewBox="0 0 16 16" fill="currentColor"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zM3 2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H3zm0 1h10a1 1 0 0 1 1 1v1H2V4a1 1 0 0 1 1-1zm11 3v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6h12z"/></svg>
                Calendario
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
                Pomodoro
                <span style="font-size:0.6rem;color:rgba(201,168,76,0.5);margin-left:auto">pronto</span>
            </a>
        </nav>
        <div class="sidebar-user">
            <div class="user-name">{{ Auth::user()->nombre }}</div>
            <div class="user-email">{{ Auth::user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
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
