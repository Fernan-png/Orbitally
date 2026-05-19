<!DOCTYPE html>
@php $user = Auth::user(); $oscuro = $user->tema !== 'claro'; @endphp
<html lang="es" class="{{ $oscuro ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orbitally · @yield('title', 'Panel')</title>
    <link rel="icon" href="{{ asset('icon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon/apple-touch-icon.png') }}">
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<script>
    document.documentElement.className = (localStorage.getItem('orbi_tema') || '{{ $oscuro ? "oscuro" : "claro" }}') === 'oscuro' ? 'dark' : 'light';
</script>
<body>

<aside class="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <span class="sidebar-logo-icon"><i class="fa-solid fa-satellite"></i></span>
        <span class="sidebar-logo-text">Orbitally<span class="sidebar-logo-subtitle">Gestor de Productividad</span></span>
    </a>

    <nav class="sidebar-nav">
        @foreach([
            ['dashboard',        'dashboard',    'fa-solid fa-gauge-high',       'Dashboard'],
            ['tasks.index',      'tasks.*',      'fa-regular fa-rectangle-list', 'Tareas'],
            ['calendar',         'calendar',     'fa-regular fa-calendar-days',  'Calendario'],
            ['categories.index', 'categories.*', 'fa-regular fa-folder-open',    'Categorías'],
            ['pomodoro.index',   'pomodoro.*',   'fa-solid fa-stopwatch',        'Pomodoro'],
        ] as [$route, $pattern, $icon, $label])
            <a href="{{ route($route) }}" title="{{ $label }}"
               class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="{{ $icon }}"></i><span class="nav-text">{{ $label }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sb-toggle">
        <button class="sb-toggle-btn" onclick="toggleSidebar()" title="Colapsar menú">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <div class="user-name nav-text">{{ $user->nombre }}</div>
        <div class="user-email nav-text">{{ $user->email }}</div>

        <form method="POST" action="{{ route('tema.toggle') }}" style="margin-bottom:10px;">
            @csrf
            <button type="submit" class="btn-logout" style="width:100%; justify-content:space-between; padding:6px 0;"
                    title="{{ $oscuro ? 'Modo claro' : 'Modo oscuro' }}">
                <span style="display:flex; align-items:center; gap:7px;">
                    <i class="{{ $oscuro ? 'fa-regular fa-sun' : 'fa-regular fa-moon' }}" style="width:15px; text-align:center;"></i>
                    <span class="nav-text">{{ $oscuro ? 'Modo claro' : 'Modo oscuro' }}</span>
                </span>
                <span class="nav-text" style="font-size:10px; opacity:0.5;">{{ $oscuro ? '☀' : '☾' }}</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout" style="width:100%;" title="Cerrar sesión">
                <i class="fa-solid fa-power-off" style="width:15px; text-align:center;"></i>
                <span class="nav-text">Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>

<button class="sb-hamburger" onclick="toggleSidebar()" aria-label="Abrir menú">
    <i class="fa-solid fa-bars"></i>
</button>
<div id="sb-overlay" class="sb-overlay" onclick="closeMobileSidebar()"></div>

@php $flash = session('success') ? ['success', session('success')] : (session('error') ? ['error', session('error')] : null) @endphp
@if($flash)
    <script>window.__flash = {type:'{{ $flash[0] }}', msg:{{ json_encode($flash[1]) }}};</script>
@endif

<main class="main-content">
    @yield('content')
</main>

<div id="toast-container"></div>

<div id="orbi-bubble" onclick="toggleOrbi()" title="Habla con Orbi">
    <i class="fa-solid fa-robot" id="orbi-icon"></i>
    <i class="fa-solid fa-xmark" id="orbi-close-icon" style="display:none;"></i>
</div>

<div id="orbi-panel">
    <div id="orbi-header">
        <div class="orbi-header-info">
            <div class="orbi-avatar"><i class="fa-solid fa-robot"></i></div>
            <div>
                <div class="orbi-name">Orbi</div>
                <div class="orbi-subtitle">Asistente de Productividad</div>
            </div>
        </div>
        <button onclick="clearOrbi()" title="Limpiar conversación" class="orbi-clear-btn">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    </div>

    <div id="orbi-messages">
        <div class="orbi-msg orbi-msg--assistant">
            <p>Hola, {{ $user->nombre }}! Soy <strong>Orbi</strong>, tu asistente de productividad.</p>
            <p>Puedo ayudarte a organizar tus tareas, planificarlas y responderte dudas sobre Orbitally. ¿En qué te ayudo?</p>
        </div>
    </div>

    <div id="orbi-input-area">
        <textarea id="orbi-input" placeholder="Escribe tu pregunta..." rows="1"></textarea>
        <button id="orbi-send" onclick="sendOrbi()" title="Enviar">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
    <div class="orbi-footer">Powered by Groq</div>
</div>

<script>
    const orbi = {
        panel:   document.getElementById('orbi-panel'),
        msgs:    document.getElementById('orbi-messages'),
        input:   document.getElementById('orbi-input'),
        btn:     document.getElementById('orbi-send'),
        csrf:    document.querySelector('meta[name="csrf-token"]').content,
        history: [],
    };

    function toggleOrbi() {
        const open = orbi.panel.classList.toggle('open');
        document.getElementById('orbi-icon').style.display       = open ? 'none' : '';
        document.getElementById('orbi-close-icon').style.display = open ? '' : 'none';
        if (open) setTimeout(() => orbi.input.focus(), 150);
        orbi.msgs.scrollTop = orbi.msgs.scrollHeight;
    }

    function clearOrbi() {
        orbi.msgs.innerHTML = '';
        orbi.history = [];
        addMsg('assistant', 'Conversación reiniciada. ¿En qué puedo ayudarte?');
    }

    function addMsg(role, text) {
        const div = document.createElement('div');
        div.className = `orbi-msg orbi-msg--${role}`;
        div.innerHTML = role === 'user' ? text : mdToHtml(text);
        orbi.msgs.appendChild(div);
        orbi.msgs.scrollTop = orbi.msgs.scrollHeight;
        return div;
    }

    function mdToHtml(t) {
        return '<p>' + t
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/^[-*] (.+)$/gm, '<li>$1</li>')
            .replace(/(<li>[\s\S]+?<\/li>)/g, '<ul>$1</ul>')
            .replace(/\n{2,}/g, '</p><p>')
            .replace(/\n/g, '<br>') + '</p>';
    }

    orbi.input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendOrbi(); }
    });
    orbi.input.addEventListener('input', () => {
        orbi.input.style.height = 'auto';
        orbi.input.style.height = Math.min(orbi.input.scrollHeight, 100) + 'px';
    });

    async function sendOrbi() {
        const msg = orbi.input.value.trim();
        if (!msg || orbi.btn.disabled) return;
        orbi.input.value = '';
        orbi.input.style.height = 'auto';
        addMsg('user', msg);
        orbi.btn.disabled = true;
        const typing = addMsg('typing', '⋯ Orbi está pensando...');
        try {
            const res  = await fetch('{{ route("assistant.chat") }}', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': orbi.csrf, 'Accept': 'application/json' },
                body:    JSON.stringify({ message: msg, history: orbi.history }),
            });
            const data = await res.json();
            typing.remove();
            if (data.error) {
                addMsg('error', data.error);
            } else {
                orbi.history.push({ role: 'user',      content: msg });
                orbi.history.push({ role: 'assistant', content: data.reply });
                addMsg('assistant', data.reply);
            }
        } catch {
            typing.remove();
            addMsg('error', 'Error de conexión. Inténtalo de nuevo.');
        } finally {
            orbi.btn.disabled = false;
            orbi.input.focus();
        }
    }
</script>

<div id="confirm-overlay">
    <div id="confirm-box">
        <div class="confirm-title">Confirmar eliminación</div>
        <div id="confirm-msg" class="confirm-msg"></div>
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button id="confirm-cancel" class="btn-secondary" style="padding:8px 18px; font-size:13px;">Cancelar</button>
            <button id="confirm-ok">Eliminar</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}" defer></script>

<script>
    const sbEl = document.querySelector('.sidebar');
    const sbOv = document.getElementById('sb-overlay');

    if (window.innerWidth > 768 && localStorage.getItem('orbi_sb') === '1')
        document.body.classList.add('sb-collapsed');

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            const open = sbEl.classList.toggle('mobile-open');
            sbOv.classList.toggle('open', open);
        } else {
            localStorage.setItem('orbi_sb', document.body.classList.toggle('sb-collapsed') ? '1' : '0');
        }
    }

    function closeMobileSidebar() {
        sbEl.classList.remove('mobile-open');
        sbOv.classList.remove('open');
    }

    window.addEventListener('resize', () => { if (window.innerWidth > 768) closeMobileSidebar(); });
</script>

@stack('scripts')
</body>
</html>
