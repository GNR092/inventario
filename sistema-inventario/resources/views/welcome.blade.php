<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root{
            /* Ajusta si ya los tienes definidos globalmente */
            --color-dorado: #d4af37;
            --color-carbon-950:#0b0f14;
            --color-carbon-900:#0f172a;
            --color-carbon-800:#1f2937;
            --color-carbon-700:#334155;
            --color-carbon-500:#6b7280;
            --color-carbon-400:#9ca3af;
            --color-carbon-300:#cbd5e1;
            --color-carbon-200:#e5e7eb;
            --color-carbon-100:#f3f4f6;
        }

        html, body { height: 100%; }
        body{
            margin:0;
            overflow-x:hidden;
            background: radial-gradient(circle at top center, #1e293b, #0b0f14);
            color: var(--color-carbon-100);
        }

        /* ====== CAPAS FONDO ====== */
        #particlesCanvas{
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .bg-halo{
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-halo::before{
            content:"";
            position:absolute;
            width: 620px;
            height: 620px;
            left: 50%;
            top: 120px;
            transform: translateX(-50%);
            border-radius: 9999px;
            filter: blur(70px);
            opacity: .18;
            background: var(--color-dorado);
            animation: haloPulse 3.8s ease-in-out infinite;
        }
        @keyframes haloPulse{
            0%,100%{ transform: translateX(-50%) scale(1); opacity:.14; }
            50%{ transform: translateX(-50%) scale(1.08); opacity:.22; }
        }

        /* Halo dinámico que sigue el mouse */
        .mouse-halo{
            position: fixed;
            width: 520px;
            height: 520px;
            border-radius: 9999px;
            filter: blur(85px);
            opacity: .20;
            background: radial-gradient(circle, rgba(212,175,55,.75), rgba(212,175,55,0) 60%);
            z-index: 2;
            pointer-events: none;
            transform: translate(-50%, -50%);
            will-change: transform;
        }

        /* ====== CONTENEDOR 3D ====== */
        .scene{
            position: relative;
            z-index: 3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 56px 0 56px;
        }
        .stage{
            width: 100%;
            max-width: 1100px;
            padding: 0 24px;
            margin: 0 auto;
            perspective: 1200px;
        }
        .parallax{
            transform-style: preserve-3d;
            will-change: transform;
        }

        /* ====== LOGO ====== */
        .logo-wrap{
            display:flex;
            justify-content:center;
            margin-top: 10px;
        }
        .logo{
            width: 168px;
            height: auto;
            transform: translateZ(25px);
            filter: drop-shadow(0 0 22px rgba(212,175,55,.38));
            animation: logoFloat 3.8s ease-in-out infinite;
            will-change: transform;
        }
        @keyframes logoFloat{
            0%,100% { transform: translateZ(25px) translateY(0px); }
            50%     { transform: translateZ(25px) translateY(-6px); }
        }

        /* ====== TITULO SVG “STROKE DRAW” ====== */
        .title-block{ text-align:center; margin-top: 10px; }
        .title-svg{
            width: min(980px, 100%);
            height: auto;
            display:block;
            margin: 0 auto;
        }
        .title-text-stroke{
            fill: rgba(212,175,55,.08);
            stroke: var(--color-dorado);
            stroke-width: 2.2px;
            paint-order: stroke;
            stroke-linejoin: round;
            stroke-linecap: round;
            stroke-dasharray: 1200;
            stroke-dashoffset: 1200;
            animation: strokeDraw 2.2s ease forwards;
            filter: drop-shadow(0 0 12px rgba(212,175,55,.25));
        }
        .title-text-fill{
            fill: var(--color-dorado);
            opacity: 0;
            animation: fillIn 0.7s ease forwards;
            animation-delay: 1.65s;
            filter: drop-shadow(0 0 20px rgba(212,175,55,.12));
        }
        @keyframes strokeDraw { to { stroke-dashoffset: 0; } }
        @keyframes fillIn { to { opacity: 1; } }

        .subtitle{
            max-width: 860px;
            margin: 16px auto 0;
            color: var(--color-carbon-300);
            line-height: 1.75;
            font-size: 1.05rem;
        }

        /* ====== BOTONES (metal pulido + brillo que recorre) ====== */
        .actions{
            margin-top: 22px;
            display:flex;
            gap: 16px;
            justify-content:center;
            flex-wrap: wrap;
        }
        .btn{
            position: relative;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap: 10px;
            padding: 14px 26px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.05rem;
            text-decoration:none;
            user-select:none;
            transform: translateZ(18px);
            transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
            will-change: transform;
            overflow:hidden;
        }
        .btn:hover{ transform: translateZ(18px) scale(1.03); }

        .btn-gold{
            background: linear-gradient(135deg, rgba(212,175,55,1), rgba(248,226,150,1));
            color: var(--color-carbon-950);
            box-shadow: 0 0 22px rgba(212,175,55,.20);
        }
        .btn-dark{
            background: rgba(31,41,55,.65);
            border: 1px solid rgba(51,65,85,.75);
            color: var(--color-carbon-100);
            backdrop-filter: blur(10px);
        }

        /* Reflejo/metal pulido animado SOLO en botones */
        .btn::before{
            content:"";
            position:absolute;
            top:-40%;
            left:-60%;
            width: 70%;
            height: 180%;
            transform: rotate(20deg);
            background: linear-gradient(
                to right,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,.55) 35%,
                rgba(255,255,255,0) 70%
            );
            opacity: .22;
            filter: blur(1px);
            animation: sheen 2.6s ease-in-out infinite;
            pointer-events:none;
        }
        @keyframes sheen{
            0%   { transform: translateX(-140%) rotate(20deg); opacity:.14; }
            45%  { opacity:.22; }
            100% { transform: translateX(260%) rotate(20deg); opacity:.10; }
        }

        /* ====== CARDS ====== */
        .cards{
            margin-top: 28px; /* más pegaditas a botones */
            display:grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }
        @media (min-width: 768px){
            .cards{ grid-template-columns: repeat(3, 1fr); gap: 18px; }
        }
        .card{
            padding: 20px 20px;
            border-radius: 18px;
            background: rgba(15,23,42,.62);
            border: 1px solid rgba(31,41,55,.75);
            box-shadow: 0 10px 30px rgba(0,0,0,.22);
            backdrop-filter: blur(10px);
            transform: translateZ(10px);
            transition: transform .25s ease, box-shadow .25s ease;
            will-change: transform;
        }
        .card:hover{
            transform: translateZ(10px) translateY(-2px);
            box-shadow: 0 0 22px rgba(212,175,55,.16);
        }
        .card h3{
            margin: 10px 0 8px;
            font-weight: 900;
            color: var(--color-carbon-100);
        }
        .card p{
            margin:0;
            color: var(--color-carbon-400);
            line-height: 1.55;
            font-size: .92rem;
        }

        /* ====== FOOTER ====== */
        .footer{
            margin-top: 34px;
            padding-top: 10px;
            color: var(--color-carbon-500);
            font-size: .9rem;
        }

        /* ====== TRANSICIÓN “APPLE” ====== */
        .apple-transition{
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            opacity: 0;
            transition: opacity .25s ease;
        }
        .apple-transition .veil{
            position:absolute;
            inset:0;
            background: radial-gradient(circle at center, rgba(212,175,55,.18), rgba(0,0,0,.95) 55%);
            filter: blur(0px);
            transform: scale(1.02);
            opacity: 0;
        }
        .apple-transition.active{
            opacity: 1;
        }
        .apple-transition.active .veil{
            animation: appleVeil .75s cubic-bezier(.2,.9,.2,1) forwards;
        }
        @keyframes appleVeil{
            0%{ opacity:0; filter: blur(0px); transform: scale(1.02); }
            55%{ opacity:1; filter: blur(6px); transform: scale(1.06); }
            100%{ opacity:1; filter: blur(18px); transform: scale(1.12); }
        }

        /* Accesibilidad: respetar reduce motion */
        @media (prefers-reduced-motion: reduce){
            .title-text-stroke, .title-text-fill, .logo, .btn::before, .bg-halo::before { animation: none !important; }
            .btn, .card { transition: none !important; }
        }
    </style>
</head>

<body>
    <!-- Partículas -->
    <canvas id="particlesCanvas"></canvas>

    <!-- Halo base + halo mouse -->
    <div class="bg-halo"></div>
    <div class="mouse-halo" id="mouseHalo" aria-hidden="true"></div>

    <!-- Transición tipo Apple -->
    <div class="apple-transition" id="appleTransition" aria-hidden="true">
        <div class="veil"></div>
    </div>

    <div class="scene">
        <div class="stage">
            <div class="parallax" id="parallaxRoot">

                <!-- Logo -->
                <div class="logo-wrap">
                    <img src="{{ asset('images/Logo-MB.svg') }}" class="logo" alt="Logo MB">
                </div>

                <!-- Título stroke draw -->
                <div class="title-block">
                    <!-- Fallback accesible -->
                    <h1 class="sr-only">Sistema de Inventario</h1>

                    <svg class="title-svg" viewBox="0 0 1200 170" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Sistema de Inventario MB">
                        <defs>
                            <filter id="softGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="2.2" result="blur"/>
                                <feMerge>
                                    <feMergeNode in="blur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>

                        <!-- Stroke (se “escribe”) -->
                        <text x="50%" y="55%" text-anchor="middle"
                              font-size="64" font-weight="900"
                              font-family="Figtree, system-ui, -apple-system, Segoe UI, Roboto, Arial"
                              class="title-text-stroke"
                              filter="url(#softGlow)">
                            Sistema de Inventario MB
                        </text>

                        <!-- Fill aparece después -->
                        <text x="50%" y="55%" text-anchor="middle"
                              font-size="64" font-weight="900"
                              font-family="Figtree, system-ui, -apple-system, Segoe UI, Roboto, Arial"
                              class="title-text-fill">
                            Sistema de Inventario MB
                        </text>
                    </svg>

                    <p class="subtitle">
                        Plataforma integral para administrar activos institucionales:
                        registra adquisiciones, almacena facturas PDF/XML, genera códigos QR
                        y centraliza la gestión del inventario con trazabilidad.
                    </p>
                </div>

                <!-- Botones -->
                <div class="actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-gold" id="goDashboard">
                            🚀 Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark">
                            🔐 Iniciar Sesión
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-gold">
                            ✨ Registrarse
                        </a>
                    @endauth
                </div>

                <!-- Cards (más cerca de botones) -->
                <div class="cards">
                    <div class="card">
                        <div style="font-size:1.7rem">📦</div>
                        <h3>Gestión de Activos</h3>
                        <p>Registro completo de mobiliario y equipo con fecha, valor, ubicación y responsable.</p>
                    </div>

                    <div class="card">
                        <div style="font-size:1.7rem">📑</div>
                        <h3>Facturación Digital</h3>
                        <p>Adjunta facturas PDF o XML y conserva respaldo fiscal por cada activo registrado.</p>
                    </div>

                    <div class="card">
                        <div style="font-size:1.7rem">🔍</div>
                        <h3>QR Inteligente</h3>
                        <p>Identifica activos rápidamente y consulta su ficha pública institucional desde el QR.</p>
                    </div>
                </div>

                <div class="footer text-center">
                    © {{ date('Y') }} MB Signature Properties
                </div>

            </div>
        </div>
    </div>

    <script>
        // =============================
        // 🧠 Profundidad 3D real + halo mouse
        // =============================
        (function(){
            const root = document.getElementById('parallaxRoot');
            const halo = document.getElementById('mouseHalo');

            let mx = window.innerWidth / 2, my = window.innerHeight / 2;
            let tx = mx, ty = my;

            const lerp = (a,b,t)=>a+(b-a)*t;

            function onMove(e){
                tx = e.clientX;
                ty = e.clientY;
            }
            window.addEventListener('mousemove', onMove, { passive:true });

            function tick(){
                mx = lerp(mx, tx, 0.08);
                my = lerp(my, ty, 0.08);

                // halo sigue el mouse
                halo.style.transform = `translate(${mx}px, ${my}px) translate(-50%, -50%)`;

                // parallax 3D (suave)
                const cx = (mx / window.innerWidth) - 0.5;
                const cy = (my / window.innerHeight) - 0.5;

                const rotY = cx * 6;   // grados
                const rotX = -cy * 5;  // grados
                const moveX = cx * 12; // px
                const moveY = cy * 10; // px

                root.style.transform = `translate3d(${moveX}px, ${moveY}px, 0) rotateX(${rotX}deg) rotateY(${rotY}deg)`;

                requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        })();

        // =============================
        // 🌌 Partículas físicas reales (canvas)
        // =============================
        (function(){
            const canvas = document.getElementById('particlesCanvas');
            const ctx = canvas.getContext('2d', { alpha: true });

            let w = canvas.width = window.innerWidth;
            let h = canvas.height = window.innerHeight;

            const DPR = Math.min(window.devicePixelRatio || 1, 2);
            function resize(){
                w = window.innerWidth; h = window.innerHeight;
                canvas.width = Math.floor(w * DPR);
                canvas.height = Math.floor(h * DPR);
                canvas.style.width = w + 'px';
                canvas.style.height = h + 'px';
                ctx.setTransform(DPR,0,0,DPR,0,0);
            }
            window.addEventListener('resize', resize, { passive:true });
            resize();

            const mouse = { x: w/2, y: h/2, active:false };
            window.addEventListener('mousemove', (e)=>{ mouse.x=e.clientX; mouse.y=e.clientY; mouse.active=true; }, { passive:true });
            window.addEventListener('mouseleave', ()=>{ mouse.active=false; }, { passive:true });

            const count = Math.min(140, Math.floor((w*h)/14000)); // se adapta
            const particles = [];

            function rand(min,max){ return Math.random()*(max-min)+min; }

            for(let i=0;i<count;i++){
                particles.push({
                    x: rand(0,w),
                    y: rand(0,h),
                    vx: rand(-0.45,0.45),
                    vy: rand(-0.45,0.45),
                    r: rand(0.9, 1.9),
                    m: rand(0.8, 1.6)
                });
            }

            function step(){
                ctx.clearRect(0,0,w,h);

                // draw connections
                for(let i=0;i<particles.length;i++){
                    const p = particles[i];

                    // física básica
                    p.x += p.vx;
                    p.y += p.vy;

                    // rebote bordes
                    if(p.x < 0){ p.x = 0; p.vx *= -1; }
                    if(p.x > w){ p.x = w; p.vx *= -1; }
                    if(p.y < 0){ p.y = 0; p.vy *= -1; }
                    if(p.y > h){ p.y = h; p.vy *= -1; }

                    // fricción suave
                    p.vx *= 0.995;
                    p.vy *= 0.995;

                    // interacción con mouse (repulsión suave + “halo”)
                    if(mouse.active){
                        const dx = p.x - mouse.x;
                        const dy = p.y - mouse.y;
                        const dist = Math.sqrt(dx*dx + dy*dy) || 1;
                        const influence = 110;
                        if(dist < influence){
                            const force = (1 - dist/influence) * 0.06;
                            p.vx += (dx/dist) * force;
                            p.vy += (dy/dist) * force;
                        }
                    }
                }

                // líneas + puntos
                for(let i=0;i<particles.length;i++){
                    const a = particles[i];

                    // punto
                    ctx.beginPath();
                    ctx.arc(a.x, a.y, a.r, 0, Math.PI*2);
                    ctx.fillStyle = 'rgba(212,175,55,0.45)';
                    ctx.fill();

                    // conexiones cercanas
                    for(let j=i+1;j<particles.length;j++){
                        const b = particles[j];
                        const dx = a.x - b.x;
                        const dy = a.y - b.y;
                        const d = Math.sqrt(dx*dx + dy*dy);

                        if(d < 120){
                            const alpha = (1 - d/120) * 0.18;
                            ctx.strokeStyle = `rgba(212,175,55,${alpha})`;
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.stroke();
                        }
                    }
                }

                requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        })();

        // =============================
        // 🎥 Transición tipo Apple al ir al Dashboard
        // =============================
        (function(){
            const btn = document.getElementById('goDashboard');
            if(!btn) return;

            const overlay = document.getElementById('appleTransition');

            btn.addEventListener('click', function(e){
                e.preventDefault();
                const href = btn.getAttribute('href');

                overlay.classList.add('active');

                // timing breve para “cinematic”
                setTimeout(()=>{
                    window.location.href = href;
                }, 620);
            });
        })();
    </script>
</body>
</html>
