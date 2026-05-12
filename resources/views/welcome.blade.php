<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orbitally — Gestor de Productividad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Icons para la web -->
    <link rel="icon" href="{{ asset('icon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon/apple-touch-icon.png') }}">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        :root {
            --space-deep: #03060f;
            --space-mid: #060d1e;
            --nebula-blue: #0a1a3a;
            --star-white: #e8edf8;
            --accent-gold: #8b5cf6;
            --accent-teal: #4dcfcf;
            --accent-violet: #8b5cf6;
            --sun-core: #c4b5fd;
            --sun-glow: #7c3aed;
        }

        body {
            background: var(--space-deep);
            color: var(--star-white);
            font-family: 'Jost', sans-serif;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        /* Campo de estrellas fondo */
        .star-field {
            position: fixed; inset: 0; z-index: 0;
            background:
                var(--space-deep),
                radial-gradient(ellipse at 20% 80%, #0d1f4420 0%, transparent 60%),
                radial-gradient(ellipse at 80% 10%, #1a0a2e18 0%, transparent 60%);
        }

        /* Estrellas fijas fondo */
        .stars-layer {
            position: fixed; inset: 0; z-index: 1;
            background-image:
                radial-gradient(1px 1px at 10% 15%, #ffffff88 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 40%, #ffffff55 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 40% 8%, #ffffffaa 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 60%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 20%, #ffffff77 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 45%, #ffffffbb 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 70%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 85%, #ffffff44 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 75%, #ffffff66 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 35% 55%, #ffffff99 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 88%, #ffffff33 0%, transparent 100%),
                radial-gradient(1px 1px at 5% 95%, #ffffff55 0%, transparent 100%),
                radial-gradient(1px 1px at 95% 5%, #ffffff66 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 50% 30%, #ffffff88 0%, transparent 100%),
                radial-gradient(1px 1px at 20% 50%, #ffffff44 0%, transparent 100%);
        }

        /* sistema solar */
        .solar-system {
            position: fixed;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            width: 0; height: 0;
        }

        /* Sol */
        .sun {
            position: absolute;
            width: 72px; height: 72px;
            left: -36px; top: -36px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #f5f3ff, var(--sun-core) 40%, var(--sun-glow) 70%, #5b21b6);
            box-shadow:
                0 0 30px 15px #8b5cf640,
                0 0 80px 40px #7c3aed20,
                0 0 150px 80px #6d28d910;
            animation: sun-pulse 4s ease-in-out infinite;
        }

        @keyframes sun-pulse {
            0%, 100% { box-shadow: 0 0 30px 15px #ffcc4440, 0 0 80px 40px #ff990020, 0 0 150px 80px #ff660010; }
            50% { box-shadow: 0 0 40px 20px #ffcc4460, 0 0 100px 50px #ff990030, 0 0 180px 100px #ff660018; }
        }

        /* Anillos de orbita de cada planeta */
        .orbit {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.25);
            transform: translate(-50%, -50%);
            animation: orbit-spin linear infinite;
        }

        .orbit-1 { width: 220px; height: 220px; animation-duration: 8s; }
        .orbit-2 { width: 380px; height: 380px; animation-duration: 16s; }
        .orbit-3 { width: 550px; height: 550px; animation-duration: 28s; }

        @keyframes orbit-spin { 
            from { 
                transform: translate(-50%, -50%) 
                rotate(0deg); 
            } 
            to { 
                transform: translate(-50%, -50%) 
                rotate(360deg); 
            } 
        }
        
        /* Tamaños para móviles (pantallas de menos de 768px) */
        @media (max-width: 767px) {
            .orbit-1 { 
                width: 140px; 
                height: 140px; 
            }
            .orbit-2 { 
                width: 220px; 
                height: 220px; 
            }
            .orbit-3 { 
                width: 310px; 
                height: 310px; 
            }
            
            /* Reducir un poco el sol en móviles para que no se vea gigante */
            .sun {
                width: 50px;
                height: 50px;
                left: -25px;
                top: -25px;
            }
        }

        /* Tamaños para tablets y escritorio (pantallas de 768px en adelante) */
        @media (min-width: 768px) {
            .orbit-1 { width: 300px; height: 300px; }
            .orbit-2 { width: 500px; height: 500px; }
            .orbit-3 { width: 750px; height: 750px; }
        }

        @media (min-width: 1440px) {
            .orbit-1 { width: 320px; height: 320px; }
            .orbit-2 { width: 560px; height: 560px; }
            .orbit-3 { width: 800px; height: 800px; }
        }

        /* Planetas */
        .planet {
            position: absolute;
            border-radius: 50%;
            top: 0; left: 50%;
            transform: translate(-50%, -50%);
        }

        .orbit-1 .planet { 
            animation: counter-spin-1 8s linear infinite; 
        }
        .orbit-2 .planet { 
            animation: counter-spin-2 16s linear infinite; 
        }
        .orbit-3 .planet { 
            animation: counter-spin-3 28s linear infinite; 
        }

        @keyframes counter-spin-1 { 
            from { 
                transform: translate(-50%, -50%) 
                rotate(0deg); 
            } 
            to { 
                transform: translate(-50%, -50%) 
                rotate(-360deg); 
            } 
        }
        @keyframes counter-spin-2 { 
            from { 
                transform: translate(-50%, -50%) 
                rotate(0deg); 
            } 
            to { 
                transform: translate(-50%, -50%) 
                rotate(-360deg); 
            } 
        }
        @keyframes counter-spin-3 { 
            from { 
                transform: translate(-50%, -50%) 
                rotate(0deg); 
            } 
            to { 
                transform: translate(-50%, -50%) 
                rotate(-360deg); 
            } 
        }

        .planet-1 {
            width: 16px; height: 16px;
            background: radial-gradient(circle at 35% 35%, #88ccff, #2255aa);
            box-shadow: 0 0 10px 4px #4488ff30;
        }
        .planet-2 {
            width: 24px; height: 24px;
            background: radial-gradient(circle at 35% 35%, #ffaa88, #aa4422);
            box-shadow: 0 0 12px 5px #ff660020;
        }
        .planet-3 {
            width: 20px; height: 20px;
            background: radial-gradient(circle at 35% 35%, #aaffcc, #227755);
            box-shadow: 0 0 10px 4px #44ff8820;
        }

        /* Texto y contenido */
        .content {
            position: fixed; inset: 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 32px;
        }

        .logo-text {
            font-family: 'Cinzel', serif;
            font-size: clamp(40px, 6vw, 72px);
            font-weight: 700;
            letter-spacing: 2.5px;
            background: linear-gradient(135deg, #c4b5fd, var(--accent-gold) 40%, #ddd6fe 60%, var(--accent-gold));
            background-clip: text;
            text-shadow: none;
            margin-bottom: 6.4px;
            animation: fade-up 1s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .tagline {
            font-family: 'Jost', sans-serif;
            font-size: clamp(12px, 1.5vw, 15.2px);
            font-weight: 300;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: rgba(255, 255, 255);
            margin-bottom: 48px;
            animation: fade-up 1s 0.2s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fade-up {
            to { opacity: 1; transform: translateY(0); }
        }

        .separator {
            width: 60px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            margin: 0 auto 40px;
            animation: fade-up 1s 0.35s ease forwards;
            opacity: 0;
        }

        .intro-text {
            font-family: 'Jost', sans-serif;
            font-size: clamp(15px, 2vw, 16.8px);
            font-weight: 300;
            color: rgba(255, 255, 255);
            max-width: 550px;
            line-height: 1.8;
            margin-bottom: 48px;
            animation: fade-up 1s 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .btn-enter {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13.6px 40px;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 3.2px;
            text-transform: uppercase;
            color: #ffffff;
            background: linear-gradient(135deg, #8b5cf6, #4c1d95);
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s;
            animation: fade-up 1s 0.7s ease forwards;
            opacity: 0;
            transform: translateY(20px);
            box-shadow: 0 4px 30px rgba(201, 168, 76, 0.2);
        }

        .btn-enter::before {
            content: '';
            position: absolute;
            top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.45), transparent);
            transform: skewX(-20deg);
            transition: left 0.4s ease;
        }

        .btn-enter:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 10px 50px rgba(201, 168, 76, 0.5);
        }
        .btn-enter:hover::before { left: 130%; }

        .btn-enter:hover .btn-enter-arrow { transform: translateX(5px); }

        .btn-enter-arrow {
            font-size: 16px;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1);
        }
        
        /* Animación de click para e l botón */
        @keyframes ripple {
            from { transform: scale(0); opacity: 0.6; }
            to   { transform: scale(4); opacity: 0; }
        }

        .btn-ripple {
            position: absolute;
            width: 60px; height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            pointer-events: none;
            animation: ripple 0.5s ease-out forwards;
            transform-origin: center;
        }

        /* Corner decoration */
        .corner-decor {
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #7c3aed;
            border-style: solid;
        }

        .corner-decor.tl {
            top: 12px;
            left: 12px;
            border-width: 1.5px 0 0 1.5px;
            border-radius: 2px 0 0 0;
        }

        .corner-decor.br {
            bottom: 12px;
            right: 12px;
            border-width: 0 1.5px 1.5px 0;
            border-radius: 0 0 2px 0;
        }
    </style>
</head>
<body>
    <div class="star-field"></div>
    <div class="stars-layer"></div>

    <div class="corner-decor tl"></div>
    <div class="corner-decor br"></div>

    <div class="solar-system">
        <div class="sun"></div>
        <div class="orbit orbit-1"><div class="planet planet-1"></div></div>
        <div class="orbit orbit-2"><div class="planet planet-2"></div></div>
        <div class="orbit orbit-3"><div class="planet planet-3"></div></div>
    </div>

    <div class="content">
        <div class="logo-text">orbitally</div>
        <div class="tagline">Gestor de Productividad</div>
        <div class="separator"></div>
        <p class="intro-text">
            Organiza tus tareas, estudios y proyectos desde un único lugar. 
            Simple, claro y diseñado para que nada se pierda en el espacio.
        </p>
        <a href="{{ route('login') }}" class="btn-enter">
            Acceder
            <span class="btn-enter-arrow">→</span>
        </a>
    </div>
    <!-- Script para animación pequeña de botón -> (m3.material.io) -->
    <script>
        document.querySelector('.btn-enter').addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('btn-ripple');
            const rect = this.getBoundingClientRect();
            ripple.style.left = (e.clientX - rect.left - 30) + 'px';
            ripple.style.top  = (e.clientY - rect.top  - 30) + 'px';
            this.appendChild(ripple);
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    </script>
</body>
</html>