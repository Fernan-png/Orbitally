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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* --- Variables --------------------------------------------------- */
        :root {
            --space-deep:    #03060f;
            --space-mid:     #080f20;
            --panel-bg:      rgba(8, 14, 30, 0.85);
            --border-subtle: rgba(255, 255, 255, 0.07);
            --accent-gold:   #c9a84c;
            --accent-teal:   #4dcfcf;
            --star-white:    #e8edf8;
            --text-dim:      rgba(180, 200, 240, 0.55);
        }

        * { box-sizing: border-box; }

        /* --- Base --------------------------------------------------- */
        body {
            background:  var(--space-deep);
            font-family: 'Jost', sans-serif;
            color:       var(--star-white);
            min-height:  100vh;
        }

        /* Fondo de estrellas */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
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

        /* --- Sidebar --------------------------------------------------- */
        .sidebar {
            position:        fixed;
            left: 0; top: 0; bottom: 0;
            width:           220px;
            background:      rgba(5, 10, 22, 0.95);
            border-right:    1px solid var(--border-subtle);
            z-index:         50;
            display:         flex;
            flex-direction:  column;
            padding:         24px 0;
            backdrop-filter: blur(12px);
        }

        .sidebar-logo {
            font-family:     'Cinzel', serif;
            font-size:       20.8px;
            font-weight:     600;
            letter-spacing:  1.92px;
            background:      linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding:         0 24px 24px;
            border-bottom:   1px solid var(--border-subtle);
        }

        .sidebar-logo span {
            display:                  block;
            font-family:              'Jost', sans-serif;
            font-size:                10.4px;
            font-weight:              300;
            letter-spacing:           4.8px;
            color:                    var(--text-dim);
            -webkit-text-fill-color:  var(--text-dim);
            margin-top:               2px;
        }

        /* --- Nav ------------------------------------------------------ */
        nav.sidebar-nav {
            flex:    1;
            padding: 24px 0;
        }

        .nav-link {
            display:         flex;
            align-items:     center;
            gap:             12px;
            padding:         10.4px 24px;
            font-size:       13.12px;
            font-weight:     400;
            letter-spacing:  0.8px;
            color:           rgba(180, 200, 240, 0.6);
            text-decoration: none;
            border-left:     2px solid transparent;
            transition:      color 0.2s, background 0.2s;
        }

        .nav-link i {
            width:      16px;
            font-size:  14px;
            opacity:    0.8;
            flex-shrink: 0;
            text-align: center;
        }

        .nav-link:hover {
            color:             var(--star-white);
            background:        rgba(255, 255, 255, 0.04);
            border-left-color: rgba(201, 168, 76, 0.4);
        }

        .nav-link.active {
            color:             var(--accent-gold);
            background:        rgba(201, 168, 76, 0.06);
            border-left-color: var(--accent-gold);
        }

        /* --- Sidebar user ------------------------------------------------------ */
        .sidebar-user {
            padding:    16px 24px;
            border-top: 1px solid var(--border-subtle);
            font-size:  12.48px;
        }

        .sidebar-user .user-name {
            font-weight:   500;
            color:         var(--star-white);
            margin-bottom: 3.2px;
        }

        .sidebar-user .user-email {
            color:         var(--text-dim);
            font-size:     11.52px;
            margin-bottom: 12px;
            overflow:      hidden;
            text-overflow: ellipsis;
            white-space:   nowrap;
        }

        .btn-logout {
            display:        flex;
            align-items:    center;
            gap:            8px;
            font-size:      12px;
            color:          rgba(180, 200, 240, 0.5);
            background:     none;
            border:         none;
            cursor:         pointer;
            padding:        0;
            letter-spacing: 0.8px;
            transition:     color 0.2s;
        }
        .btn-logout:hover { color: #ff7755; }

        /* --- Main content------------------------------------------------------ */
        .main-content {
            margin-left: 220px;
            min-height:  100vh;
            padding:     32px;
            position:    relative;
            z-index:     1;
        }

        /* --- Panel ------------------------------------------------------ */
        .panel {
            background:      var(--panel-bg);
            border:          1px solid var(--border-subtle);
            border-radius:   4px;
            backdrop-filter: blur(8px);
        }

        /* --- Tabs ------------------------------------------------------ */
        .tabs-bar {
            display:       flex;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 32px;
        }

        .tab-btn {
            padding:         10.4px 22.4px;
            font-family:     'Jost', sans-serif;
            font-size:       12.8px;
            font-weight:     400;
            letter-spacing:  1.28px;
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

        .tab-btn:hover { color: var(--star-white); }
        .tab-btn.active {
            color:               var(--accent-gold);
            border-bottom-color: var(--accent-gold);
        }

        /* --- Page header ------------------------------------------------------ */
        .page-header { margin-bottom: 32px; }

        .page-title {
            font-family:    'Cinzel', serif;
            font-size:      22.4px;
            font-weight:    600;
            letter-spacing: 1.28px;
            color:          var(--star-white);
        }

        .page-subtitle {
            font-size:  12.8px;
            color:      var(--text-dim);
            margin-top: 4px;
        }

        /* --- Buttons --------------------------------------------------- */
        .btn-primary {
            display:         inline-flex;
            align-items:     center;
            gap:             8px;
            padding:         8.8px 20px;
            font-family:     'Jost', sans-serif;
            font-size:       12.8px;
            font-weight:     500;
            letter-spacing:  0.8px;
            color:           #03060f;
            background:      linear-gradient(135deg, #e8d5a0, var(--accent-gold));
            border:          none;
            border-radius:   2px;
            cursor:          pointer;
            text-decoration: none;
            transition:      opacity 0.2s, transform 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-secondary {
            display:         inline-flex;
            align-items:     center;
            gap:             8px;
            padding:         8.8px 20px;
            font-family:     'Jost', sans-serif;
            font-size:       12.8px;
            font-weight:     400;
            letter-spacing:  0.8px;
            color:           rgba(180, 200, 240, 0.7);
            background:      rgba(255, 255, 255, 0.05);
            border:          1px solid var(--border-subtle);
            border-radius:   2px;
            cursor:          pointer;
            text-decoration: none;
            transition:      background 0.2s, color 0.2s;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            color:      var(--star-white);
        }

        /* --- Form inputs -------------------------------------------------- */
        .form-input {
            width:         100%;
            background:    rgba(255, 255, 255, 0.05);
            border:        1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            padding:       9.6px 14.4px;
            font-family:   'Jost', sans-serif;
            font-size:     13.6px;
            color:         var(--star-white);
            outline:       none;
            transition:    border-color 0.2s;
        }
        .form-input::placeholder { color: rgba(180, 200, 240, 0.3); }
        .form-input:focus        { border-color: rgba(201, 168, 76, 0.5); }

        .form-label {
            display:        block;
            font-size:      12px;
            font-weight:    500;
            letter-spacing: 1.28px;
            color:          var(--text-dim);
            margin-bottom:  6.4px;
            text-transform: uppercase;
        }

        /* --- Alerts ------------------------------------------------------ */
        .alert-success {
            padding:       12px 16px;
            background:    rgba(77, 207, 207, 0.1);
            border:        1px solid rgba(77, 207, 207, 0.3);
            border-radius: 2px;
            font-size:     13.12px;
            color:         var(--accent-teal);
            margin-bottom: 24px;
        }

        .alert-error {
            padding:       12px 16px;
            background:    rgba(255, 100, 80, 0.1);
            border:        1px solid rgba(255, 100, 80, 0.3);
            border-radius: 2px;
            font-size:     13.12px;
            color:         #ff8866;
            margin-bottom: 24px;
        }

        /* --- Modal ------------------------------------------------------ */
        .modal-overlay {
            position:        fixed;
            inset:           0;
            z-index:         100;
            background:      rgba(3, 6, 15, 0.8);
            backdrop-filter: blur(4px);
            display:         flex;
            align-items:     center;
            justify-content: center;
        }

        .modal-box {
            background:    #070e20;
            border:        1px solid rgba(201, 168, 76, 0.2);
            border-radius: 4px;
            width:         100%;
            max-width:     480px;
            padding:       32px;
        }

        .modal-title {
            font-family:    'Cinzel', serif;
            font-size:      17.6px;
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

    {{-- -----------------------------------------------------------
         Sidebar
    ------------------------------------------------------------ --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            Orbitally
            <span>Gestor de Productividad</span>
        </div>

        {{-- Navegación principal --}}
        <nav class="sidebar-nav">

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-regular fa-clock"></i>
                Dashboard
            </a>

            <a href="{{ route('tasks.index') }}"
               class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="fa-regular fa-rectangle-list"></i>
                Tareas
            </a>

            <a href="{{ route('calendar') }}"
               class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <i class="fa-regular fa-calendar"></i>
                Calendario
            </a>

            <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="fa-regular fa-tag"></i>
                Categorías
            </a>

            <a href="#" class="nav-link">
                <i class="fa-regular fa-lock"></i>
                Pomodoro
                <span class="ml-auto" style="font-size: 10px; color: rgba(201,168,76,0.5)">pronto</span>
            </a>

        </nav>

        {{-- Usuario y logout --}}
        <div class="sidebar-user">
            <div class="user-name">{{ Auth::user()->nombre }}</div>
            <div class="user-email">{{ Auth::user()->email }}</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-regular fa-right-from-bracket" style="font-size: 12px;"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>

    </aside>

    {{-- -----------------------------------------------------------
         Contenido principal
    ------------------------------------------------------------ --}}
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