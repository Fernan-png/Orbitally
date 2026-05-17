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
    <script>tailwind.config = { darkMode: 'class' }</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo" style="text-decoration:none; display:block; cursor:pointer;">
            <span class="sidebar-logo-icon"><i class="fa-solid fa-satellite"></i></span>
            <span class="sidebar-logo-text">Orbitally<span class="sidebar-logo-subtitle">Gestor de Productividad</span></span>
        </a>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" title="Dashboard"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('tasks.index') }}" title="Tareas"
               class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="fa-regular fa-rectangle-list"></i>
                <span class="nav-text">Tareas</span>
            </a>
            <a href="{{ route('calendar') }}" title="Calendario"
               class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <i class="fa-regular fa-calendar-days"></i>
                <span class="nav-text">Calendario</span>
            </a>
            <a href="{{ route('categories.index') }}" title="Categorías"
               class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fa-regular fa-folder-open"></i>
                <span class="nav-text">Categorías</span>
            </a>
            <a href="{{ route('pomodoro.index') }}" title="Pomodoro"
               class="nav-link {{ request()->routeIs('pomodoro.*') ? 'active' : '' }}">
                <i class="fa-solid fa-stopwatch"></i>
                <span class="nav-text">Pomodoro</span>
            </a>
        </nav>

        <div class="sb-toggle">
            <button class="sb-toggle-btn" onclick="toggleSidebar()" title="Colapsar menú">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <div class="sidebar-user">
            <div class="user-name nav-text">{{ Auth::user()->nombre }}</div>
            <div class="user-email nav-text">{{ Auth::user()->email }}</div>

            <form method="POST" action="{{ route('tema.toggle') }}" style="margin-bottom:10px;">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%; justify-content:space-between; padding:6px 0;"
                        title="{{ Auth::user()->tema === 'oscuro' ? 'Modo claro' : 'Modo oscuro' }}">
                    <span style="display:flex; align-items:center; gap:7px;">
                        @if(Auth::user()->tema === 'oscuro')
                            <i class="fa-regular fa-sun" style="width:15px; text-align:center;"></i>
                        @else
                            <i class="fa-regular fa-moon" style="width:15px; text-align:center;"></i>
                        @endif
                        <span class="nav-text">{{ Auth::user()->tema === 'oscuro' ? 'Modo claro' : 'Modo oscuro' }}</span>
                    </span>
                    <span class="nav-text" style="font-size:10px; opacity:0.5;">
                        {{ Auth::user()->tema === 'oscuro' ? '☀' : '☾' }}
                    </span>
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

    @if(session('success'))
        <script>window.__flash = { type: 'success', msg: {{ json_encode(session('success')) }} };</script>
    @endif
    @if(session('error'))
        <script>window.__flash = { type: 'error', msg: {{ json_encode(session('error')) }} };</script>
    @endif

    <main class="main-content">
        @yield('content')
    </main>

    <div id="toast-container"></div>

    {{-- AI Assistant Floating Chat --}}
    <div id="orbi-bubble" onclick="toggleOrbi()" title="Habla con Orbi">
        <i class="fa-solid fa-robot" id="orbi-icon"></i>
        <i class="fa-solid fa-xmark" id="orbi-close-icon" style="display:none;"></i>
    </div>

    <div id="orbi-panel">
        <div id="orbi-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:50%; background:var(--accent-gold)22;
                            border:1px solid var(--accent-gold)44; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-robot" style="font-size:14px; color:var(--accent-gold);"></i>
                </div>
                <div>
                    <div style="font-family:'Cinzel',serif; font-size:13px; font-weight:600; letter-spacing:1px; color:var(--star-white);">Orbi</div>
                    <div style="font-size:10px; color:var(--text-dim); letter-spacing:0.05em;">Asistente de Productividad</div>
                </div>
            </div>
            <button onclick="clearOrbi()" title="Limpiar conversación"
                    style="background:none; border:none; cursor:pointer; color:var(--text-dim); font-size:12px; padding:4px 8px;
                           border-radius:2px; transition:color 0.2s;"
                    onmouseover="this.style.color='var(--star-white)'" onmouseout="this.style.color='var(--text-dim)'">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

        <div id="orbi-messages">
            <div class="orbi-msg orbi-msg--assistant">
                <p>Hola, {{ Auth::user()->nombre }}! Soy <strong>Orbi</strong>, tu asistente de productividad.</p>
                <p>Puedo ayudarte a organizar tus tareas, planificarlas y responderte dudas sobre Orbitally. ¿En qué te ayudo?</p>
            </div>
        </div>

        <div id="orbi-input-area">
            <textarea id="orbi-input" placeholder="Escribe tu pregunta..." rows="1"></textarea>
            <button id="orbi-send" onclick="sendOrbi()" title="Enviar">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
        <div style="text-align:center; font-size:10px; color:var(--text-muted); padding:6px 0 2px;">
            Powered by Groq
        </div>
    </div>

    <style>
        #orbi-bubble {
            position: fixed; bottom: 28px; right: 28px;
            width: 52px; height: 52px; border-radius: 50%;
            background: var(--accent-gold); border: none; cursor: pointer; z-index: 200;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(139,92,246,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        #orbi-bubble:hover { transform: scale(1.08); box-shadow: 0 6px 28px rgba(139,92,246,0.55); }
        #orbi-bubble i { font-size: 20px; color: #fff; }

        #orbi-panel {
            position: fixed; bottom: 92px; right: 28px;
            width: 360px; max-height: 520px;
            background: var(--panel-bg); border: 1px solid var(--border-subtle); border-radius: 10px;
            box-shadow: var(--panel-shadow), 0 8px 40px rgba(0,0,0,0.35);
            z-index: 199; display: flex; flex-direction: column; backdrop-filter: blur(20px);
            opacity: 0; transform: translateY(12px) scale(0.97); pointer-events: none;
            transition: opacity 0.22s, transform 0.22s;
        }
        #orbi-panel.open { opacity: 1; transform: none; pointer-events: all; }

        #orbi-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; border-bottom: 1px solid var(--border-subtle); flex-shrink: 0;
        }
        #orbi-messages {
            flex: 1; overflow-y: auto; padding: 14px 16px;
            display: flex; flex-direction: column; gap: 12px;
            scrollbar-width: thin; scrollbar-color: var(--border-subtle) transparent;
        }
        .orbi-msg {
            max-width: 88%; font-size: 13px; line-height: 1.55;
            padding: 10px 13px; border-radius: 8px;
        }
        .orbi-msg p { margin: 0 0 6px; }
        .orbi-msg p:last-child { margin-bottom: 0; }
        .orbi-msg ul { margin: 6px 0 0; padding-left: 18px; }
        .orbi-msg li { margin-bottom: 3px; }
        .orbi-msg--assistant {
            background: rgba(139,92,246,0.08); border: 1px solid rgba(139,92,246,0.15);
            color: var(--star-white); align-self: flex-start; border-bottom-left-radius: 2px;
        }
        .orbi-msg--user {
            background: var(--accent-gold); color: #fff;
            align-self: flex-end; border-bottom-right-radius: 2px;
        }
        .orbi-msg--typing {
            background: rgba(139,92,246,0.06); border: 1px solid rgba(139,92,246,0.12);
            color: var(--text-dim); align-self: flex-start; font-style: italic; font-size: 12px;
        }
        .orbi-msg--error {
            background: rgba(255,80,80,0.08); border: 1px solid rgba(255,80,80,0.2);
            color: #ff8888; align-self: flex-start;
        }
        #orbi-input-area {
            display: flex; align-items: flex-end; gap: 8px;
            padding: 10px 14px; border-top: 1px solid var(--border-subtle); flex-shrink: 0;
        }
        #orbi-input {
            flex: 1; background: rgba(255,255,255,0.04); border: 1px solid var(--border-subtle);
            border-radius: 6px; color: var(--star-white); font-family: 'Jost', sans-serif;
            font-size: 13px; padding: 8px 11px; resize: none; max-height: 100px;
            outline: none; transition: border-color 0.2s; scrollbar-width: none;
        }
        #orbi-input:focus { border-color: rgba(139,92,246,0.4); }
        #orbi-input::placeholder { color: var(--text-muted); }
        #orbi-send {
            width: 34px; height: 34px; border-radius: 6px; background: var(--accent-gold);
            border: none; cursor: pointer; color: #fff; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: opacity 0.2s;
        }
        #orbi-send:hover { opacity: 0.85; }
        #orbi-send:disabled { opacity: 0.4; cursor: not-allowed; }
        html.light #orbi-bubble { box-shadow: 0 4px 20px rgba(109,40,217,0.3); }
        html.light .orbi-msg--assistant { background: rgba(109,40,217,0.06); border-color: rgba(109,40,217,0.12); }
        html.light #orbi-input { background: rgba(0,0,0,0.03); }
    </style>

    <script>
        const orbi = {
            panel:   document.getElementById('orbi-panel'),
            msgs:    document.getElementById('orbi-messages'),
            input:   document.getElementById('orbi-input'),
            btn:     document.getElementById('orbi-send'),
            csrf:    document.querySelector('meta[name="csrf-token"]').content,
            history: [],   // historial de la conversación
        };

        function toggleOrbi() {
            const open = orbi.panel.classList.toggle('open');
            document.getElementById('orbi-icon').style.display      = open ? 'none' : '';
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
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': orbi.csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ message: msg, history: orbi.history }),
                });
                const data = await res.json();
                typing.remove();
                if (data.error) {
                    addMsg('error', data.error);
                } else {
                    // Guardar el intercambio en el historial
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
            <div style="font-family:'Cinzel',serif; font-size:17px; font-weight:600;
                        color:var(--star-white); margin-bottom:8px; letter-spacing:0.5px;">
                Confirmar eliminación
            </div>
            <div id="confirm-msg" style="font-size:13px; color:var(--text-dim);
                                         margin-bottom:24px; line-height:1.6;"></div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button id="confirm-cancel" class="btn-secondary" style="padding:8px 18px; font-size:13px;">
                    Cancelar
                </button>
                <button id="confirm-ok">Eliminar</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        const sbEl  = document.querySelector('.sidebar');
        const sbOv  = document.getElementById('sb-overlay');

        // Restaurar estado colapsado en escritorio
        if (window.innerWidth > 768 && localStorage.getItem('orbi_sb') === '1') {
            document.body.classList.add('sb-collapsed');
        }

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                const open = sbEl.classList.toggle('mobile-open');
                sbOv.classList.toggle('open', open);
            } else {
                const collapsed = document.body.classList.toggle('sb-collapsed');
                localStorage.setItem('orbi_sb', collapsed ? '1' : '0');
            }
        }

        function closeMobileSidebar() {
            sbEl.classList.remove('mobile-open');
            sbOv.classList.remove('open');
        }

        // Al redimensionar: limpiar estado móvil si se pasa a escritorio
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) closeMobileSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
