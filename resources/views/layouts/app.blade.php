<!DOCTYPE html>
<html lang="es" class="{{ Auth::user()->tema === 'claro' ? 'light' : 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally · @yield('title', 'Panel')</title>
    <link rel="icon" href="{{ asset('icon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon/apple-touch-icon.png') }}">

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
            --star-white:       #150f3a;
            --text-dim:         rgba(55, 20, 120, 0.78);
            --text-muted:       rgba(55, 20, 120, 0.52);
            --btn-logout-color: rgba(55, 20, 120, 0.62);
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
            color:             #ffffff;
            background:        rgba(139, 92, 246, 0.22);
            border-left-color: var(--accent-gold);
        }
        html.light .nav-link.active {
            color:             #ffffff;
            background:        rgba(109, 40, 217, 0.72);
            border-left-color: #6d28d9;
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
            margin-left:       220px;
            min-height:        100vh;
            padding:           32px;
            position:          relative;
            z-index:           1;
            background-image:
                radial-gradient(1px   1px   at 12% 10%, rgba(255,255,255,0.60) 0%, transparent 100%),
                radial-gradient(1px   1px   at 28% 34%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 44%  6%, rgba(255,255,255,0.70) 0%, transparent 100%),
                radial-gradient(1px   1px   at 59% 22%, rgba(255,255,255,0.45) 0%, transparent 100%),
                radial-gradient(1px   1px   at 73% 48%, rgba(255,255,255,0.30) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 87% 15%, rgba(255,255,255,0.65) 0%, transparent 100%),
                radial-gradient(1px   1px   at 18% 58%, rgba(255,255,255,0.40) 0%, transparent 100%),
                radial-gradient(1px   1px   at 36% 72%, rgba(255,255,255,0.50) 0%, transparent 100%),
                radial-gradient(1px   1px   at 51% 85%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 65% 63%, rgba(255,255,255,0.60) 0%, transparent 100%),
                radial-gradient(1px   1px   at 80% 78%, rgba(255,255,255,0.40) 0%, transparent 100%),
                radial-gradient(1px   1px   at 94% 40%, rgba(255,255,255,0.55) 0%, transparent 100%),
                radial-gradient(1px   1px   at  6% 88%, rgba(255,255,255,0.30) 0%, transparent 100%),
                radial-gradient(1px   1px   at 22% 50%, rgba(255,255,255,0.45) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 48% 42%, rgba(255,255,255,0.55) 0%, transparent 100%),
                radial-gradient(1px   1px   at 70% 92%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1px   1px   at 83% 28%, rgba(255,255,255,0.50) 0%, transparent 100%),
                radial-gradient(1px   1px   at 38% 18%, rgba(255,255,255,0.40) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 56% 75%, rgba(255,255,255,0.65) 0%, transparent 100%),
                radial-gradient(1px   1px   at 91% 55%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1px   1px   at 15% 76%, rgba(255,255,255,0.45) 0%, transparent 100%),
                radial-gradient(1px   1px   at 33% 94%, rgba(255,255,255,0.30) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 77% 12%, rgba(255,255,255,0.70) 0%, transparent 100%),
                radial-gradient(1px   1px   at 62% 52%, rgba(255,255,255,0.40) 0%, transparent 100%),
                radial-gradient(1px   1px   at  3% 44%, rgba(255,255,255,0.50) 0%, transparent 100%);
            background-attachment: fixed;
        }
        html.light .main-content {
            background-image:
                radial-gradient(1px   1px   at 12% 10%, rgba(109,40,217,0.18) 0%, transparent 100%),
                radial-gradient(1px   1px   at 28% 34%, rgba(109,40,217,0.10) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 44%  6%, rgba(109,40,217,0.22) 0%, transparent 100%),
                radial-gradient(1px   1px   at 59% 22%, rgba(109,40,217,0.14) 0%, transparent 100%),
                radial-gradient(1px   1px   at 73% 48%, rgba(109,40,217,0.08) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 87% 15%, rgba(109,40,217,0.20) 0%, transparent 100%),
                radial-gradient(1px   1px   at 18% 58%, rgba(109,40,217,0.12) 0%, transparent 100%),
                radial-gradient(1px   1px   at 36% 72%, rgba(109,40,217,0.16) 0%, transparent 100%),
                radial-gradient(1px   1px   at 51% 85%, rgba(109,40,217,0.10) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 65% 63%, rgba(109,40,217,0.18) 0%, transparent 100%),
                radial-gradient(1px   1px   at 80% 78%, rgba(109,40,217,0.12) 0%, transparent 100%),
                radial-gradient(1px   1px   at 94% 40%, rgba(109,40,217,0.16) 0%, transparent 100%),
                radial-gradient(1px   1px   at  6% 88%, rgba(109,40,217,0.08) 0%, transparent 100%),
                radial-gradient(1px   1px   at 22% 50%, rgba(109,40,217,0.14) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 48% 42%, rgba(109,40,217,0.18) 0%, transparent 100%),
                radial-gradient(1px   1px   at 70% 92%, rgba(109,40,217,0.10) 0%, transparent 100%),
                radial-gradient(1px   1px   at 83% 28%, rgba(109,40,217,0.15) 0%, transparent 100%),
                radial-gradient(1px   1px   at 38% 18%, rgba(109,40,217,0.12) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 56% 75%, rgba(109,40,217,0.20) 0%, transparent 100%),
                radial-gradient(1px   1px   at 91% 55%, rgba(109,40,217,0.10) 0%, transparent 100%),
                radial-gradient(1px   1px   at 15% 76%, rgba(109,40,217,0.14) 0%, transparent 100%),
                radial-gradient(1px   1px   at 33% 94%, rgba(109,40,217,0.08) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 77% 12%, rgba(109,40,217,0.22) 0%, transparent 100%),
                radial-gradient(1px   1px   at 62% 52%, rgba(109,40,217,0.12) 0%, transparent 100%),
                radial-gradient(1px   1px   at  3% 44%, rgba(109,40,217,0.16) 0%, transparent 100%);
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
        .tab-btn.active {
            color:              #ffffff;
            background:         rgba(139, 92, 246, 0.18);
            border-bottom-color: var(--accent-gold);
            border-radius:      3px 3px 0 0;
        }
        html.light .tab-btn.active {
            color:              #ffffff;
            background:         rgba(109, 40, 217, 0.65);
            border-bottom-color: #6d28d9;
        }

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

        /* ── Toasts ─────────────────────────────────────────────────── */
        #toast-container {
            position:       fixed;
            bottom:         24px;
            right:          24px;
            z-index:        300;
            display:        flex;
            flex-direction: column;
            gap:            10px;
            align-items:    flex-end;
            pointer-events: none;
        }
        .toast {
            pointer-events:    all;
            display:           flex;
            align-items:       center;
            gap:               10px;
            padding:           12px 16px;
            border-radius:     4px;
            font-size:         13px;
            font-family:       'Jost', sans-serif;
            color:             var(--star-white);
            background:        var(--panel-bg);
            border:            1px solid var(--border-subtle);
            border-left-width: 3px;
            box-shadow:        0 8px 32px rgba(0,0,0,0.35);
            backdrop-filter:   blur(8px);
            min-width:         220px;
            max-width:         320px;
            transform:         translateX(calc(100% + 32px));
            opacity:           0;
            transition:        transform 0.32s cubic-bezier(0.34, 1.3, 0.64, 1), opacity 0.25s;
        }
        .toast.visible  { transform: translateX(0); opacity: 1; }
        .toast.t-success { border-left-color: #4dcfcf; }
        .toast.t-error   { border-left-color: #ff6644; }
        .toast.t-info    { border-left-color: #a78bfa; }

        /* ── Confirm delete modal ───────────────────────────────────── */
        #confirm-overlay {
            position:        fixed;
            inset:           0;
            z-index:         200;
            display:         flex;
            align-items:     center;
            justify-content: center;
            background:      rgba(3, 6, 15, 0.88);
            backdrop-filter: blur(6px);
            opacity:         0;
            pointer-events:  none;
            transition:      opacity 0.2s;
        }
        #confirm-overlay.open { opacity: 1; pointer-events: all; }
        html.light #confirm-overlay { background: rgba(160, 170, 200, 0.78); }
        #confirm-box {
            background:    var(--panel-bg);
            border:        1px solid rgba(139, 92, 246, 0.25);
            border-radius: 4px;
            width:         100%;
            max-width:     400px;
            padding:       28px 32px;
            box-shadow:    0 24px 64px rgba(0,0,0,0.5);
            margin:        20px;
            transform:     translateY(10px);
            transition:    transform 0.22s;
        }
        #confirm-overlay.open #confirm-box { transform: translateY(0); }
    </style>

    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo" style="text-decoration:none; display:block; cursor:pointer;">
            Orbitally
            <span>Gestor de Productividad</span>
        </a>

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

    @if(session('success'))
        <script>window.__flash = { type: 'success', msg: {{ json_encode(session('success')) }} };</script>
    @endif
    @if(session('error'))
        <script>window.__flash = { type: 'error', msg: {{ json_encode(session('error')) }} };</script>
    @endif

    <main class="main-content">
        @yield('content')
    </main>

    {{-- Toast container --}}
    <div id="toast-container"></div>

    {{-- Confirm delete modal --}}
    <div id="confirm-overlay">
        <div id="confirm-box">
            <div style="font-family:'Cinzel',serif; font-size:17px; font-weight:600;
                        color:var(--star-white); margin-bottom:8px; letter-spacing:0.5px;">
                Confirmar eliminación
            </div>
            <div id="confirm-msg" style="font-size:13px; color:var(--text-dim);
                                         margin-bottom:24px; line-height:1.6;"></div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button id="confirm-cancel" class="btn-secondary"
                        style="padding:8px 18px; font-size:13px;">
                    Cancelar
                </button>
                <button id="confirm-ok"
                        style="padding:8px 18px; font-family:'Jost',sans-serif; font-size:13px;
                               font-weight:500; letter-spacing:0.3px; color:#fff;
                               background:rgba(210,50,30,0.82); border:1px solid rgba(210,50,30,0.35);
                               border-radius:3px; cursor:pointer; transition:background 0.2s;"
                        onmouseover="this.style.background='rgba(210,50,30,1)'"
                        onmouseout="this.style.background='rgba(210,50,30,0.82)'">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // Muestra una notificación toast en la esquina inferior derecha
    function showToast(msg, type, duration) {
        if (!type) type = 'success';
        if (!duration) duration = 3200;

        let icon  = 'fa-circle-check';
        let color = '#4dcfcf';
        if (type === 'error') { icon = 'fa-circle-xmark'; color = '#ff6644'; }
        if (type === 'info')  { icon = 'fa-circle-info';  color = '#a78bfa'; }

        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast t-' + type;
        toast.innerHTML =
            '<i class="fa-solid ' + icon + '" style="color:' + color + '; font-size:14px; flex-shrink:0;"></i>' +
            '<span>' + msg + '</span>';
        container.appendChild(toast);

        setTimeout(function() { toast.classList.add('visible'); }, 10);
        setTimeout(function() {
            toast.classList.remove('visible');
            setTimeout(function() { toast.remove(); }, 350);
        }, duration);
    }

    window.showToast = showToast;

    // Mostrar flash message del servidor como toast
    if (window.__flash) {
        showToast(window.__flash.msg, window.__flash.type);
    }

    // Cambio de tema sin recargar la página
    const themeForm = document.querySelector('form[action*="/tema"]');
    if (themeForm) {
        themeForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const html = document.documentElement;
            const switchingToLight = html.classList.contains('dark');

            if (switchingToLight) {
                html.classList.remove('dark');
                html.classList.add('light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
            }

            const btn        = themeForm.querySelector('button[type="submit"]');
            const textSpan   = btn.querySelector('span:first-child');
            const symbolSpan = btn.querySelector('span:last-child');

            if (switchingToLight) {
                textSpan.innerHTML     = '<i class="fa-regular fa-moon" style="width:15px;text-align:center;"></i> Modo oscuro';
                symbolSpan.textContent = '☾';
                btn.title              = 'Cambiar a modo oscuro';
            } else {
                textSpan.innerHTML     = '<i class="fa-regular fa-sun" style="width:15px;text-align:center;"></i> Modo claro';
                symbolSpan.textContent = '☀';
                btn.title              = 'Cambiar a modo claro';
            }

            fetch(themeForm.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                body: new FormData(themeForm),
            });
        });
    }

    // Modal de confirmación para eliminar elementos
    const confirmOverlay = document.getElementById('confirm-overlay');
    const confirmMsg     = document.getElementById('confirm-msg');
    const confirmOk      = document.getElementById('confirm-ok');
    const confirmCancel  = document.getElementById('confirm-cancel');
    let formToDelete = null;

    function openDeleteModal(msg, form) {
        confirmMsg.textContent = msg;
        formToDelete = form;
        confirmOverlay.classList.add('open');
    }

    function closeDeleteModal() {
        confirmOverlay.classList.remove('open');
        formToDelete = null;
    }

    confirmCancel.addEventListener('click', closeDeleteModal);

    confirmOverlay.addEventListener('click', function(e) {
        if (e.target === confirmOverlay) closeDeleteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });

    confirmOk.addEventListener('click', function() {
        if (!formToDelete) return;
        const form = formToDelete;
        closeDeleteModal();
        HTMLFormElement.prototype.submit.call(form);
    });

    // Interceptar formularios DELETE y mostrar el modal en lugar del confirm() nativo
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const isDeleteForm = form.querySelector('input[name="_method"][value="DELETE"]');
        if (!isDeleteForm) return;

        e.preventDefault();
        const msg = form.dataset.confirm || '¿Estás seguro de que quieres eliminar este elemento?';
        openDeleteModal(msg, form);
    });
    </script>

    @stack('scripts')
</body>
</html>