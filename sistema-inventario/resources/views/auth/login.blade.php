<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión - MB</title>

@vite(['resources/css/app.css','resources/js/app.js'])

<style>
:root{
    --color-dorado:#d4af37;
    --color-carbon-950:#0b0f14;
    --color-carbon-900:#0f172a;
    --color-carbon-800:#1f2937;
    --color-carbon-700:#334155;
    --color-carbon-400:#9ca3af;
    --color-carbon-300:#cbd5e1;
}

html,body{
    margin:0;
    height:100%;
    overflow-x:hidden;
}

body{
    background: radial-gradient(circle at top center, #1e293b, #0b0f14);
    color:white;
}

/* ===== CANVAS TELARAÑA ===== */
#particlesCanvas{
    position:fixed;
    inset:0;
    z-index:0;
    pointer-events:none;
}

/* ===== HALO SUPERIOR ===== */
.bg-halo{
    position:fixed;
    inset:0;
    pointer-events:none;
}
.bg-halo::before{
    content:"";
    position:absolute;
    width:620px;
    height:620px;
    left:50%;
    top:120px;
    transform:translateX(-50%);
    border-radius:9999px;
    filter:blur(70px);
    opacity:.18;
    background:var(--color-dorado);
}

/* ===== CARD LOGIN ===== */
.login-card{
    position:relative;
    z-index:2;
    width:100%;
    max-width:520px;
    padding:60px 45px;
    border-radius:30px;
    background:rgba(15,23,42,.88);
    border:1px solid rgba(212,175,55,.6);
    backdrop-filter:blur(25px);
    box-shadow:0 30px 80px rgba(0,0,0,.6);
    transform-style:preserve-3d;
    transition:transform .25s ease;
}

/* ===== LOGO ===== */
.logo{
    width:170px;
    filter:drop-shadow(0 0 30px rgba(212,175,55,.6));
    animation:floatLogo 5s ease-in-out infinite;
}
@keyframes floatLogo{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-8px)}
}

/* ===== INPUT ===== */
.input-field{
    width:100%;
    padding:14px 20px;
    border-radius:16px;
    background:var(--color-carbon-950);
    border:1px solid var(--color-carbon-700);
    color:white;
    transition:.3s;
}
.input-field:focus{
    border-color:var(--color-dorado);
    box-shadow:0 0 15px rgba(212,175,55,.7);
    outline:none;
}

/* ===== BOTÓN ORO ===== */
.btn-gold{
    position:relative;
    width:100%;
    padding:14px;
    border-radius:16px;
    font-weight:700;
    background:linear-gradient(135deg,#d4af37,#f7e7a9);
    color:#111827;
    overflow:hidden;
    transition:.3s;
}
.btn-gold:hover{
    transform:scale(1.06);
    box-shadow:0 0 30px rgba(212,175,55,.8);
}

/* efecto metálico animado */
.btn-gold::before{
    content:"";
    position:absolute;
    top:-40%;
    left:-70%;
    width:60%;
    height:200%;
    background:linear-gradient(to right,
        rgba(255,255,255,0),
        rgba(255,255,255,.8),
        rgba(255,255,255,0));
    transform:rotate(20deg);
    animation:sheen 2.8s infinite;
}
@keyframes sheen{
    0%{left:-70%}
    100%{left:170%}
}
</style>
</head>

<body class="flex items-center justify-center min-h-screen relative">

<canvas id="particlesCanvas"></canvas>
<div class="bg-halo"></div>

<div class="login-card" id="loginCard">

    <div class="text-center mb-10">
        <img src="{{ asset('images/Logo-MB.svg') }}" class="logo mx-auto">
        <h1 class="mt-6 text-2xl font-bold" style="color:var(--color-dorado)">
            MB Signature Properties
        </h1>
        <p class="mt-2 text-sm" style="color:var(--color-carbon-400)">
            Acceso al Sistema Institucional
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-7">
        @csrf

        <div>
            <label class="block text-sm mb-2" style="color:var(--color-carbon-300)">
                Correo Electrónico
            </label>
            <input type="email" name="email" required autofocus class="input-field">
        </div>

        <div>
            <label class="block text-sm mb-2" style="color:var(--color-carbon-300)">
                Contraseña
            </label>
            <input type="password" name="password" required class="input-field">
        </div>

        <button type="submit" class="btn-gold">
            Iniciar Sesión
        </button>

        <div class="text-center text-sm mt-4" style="color:var(--color-carbon-400)">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" style="color:var(--color-dorado)">
                Crear cuenta
            </a>
        </div>
    </form>
</div>

<script>
/* ===============================
   🌌 PARTÍCULAS TELARAÑA REAL
=================================*/

const canvas = document.getElementById('particlesCanvas');
const ctx = canvas.getContext('2d');

let w = canvas.width = window.innerWidth;
let h = canvas.height = window.innerHeight;

window.addEventListener('resize', ()=>{
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
});

const mouse = { x:w/2, y:h/2 };

window.addEventListener('mousemove', e=>{
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

const particles = [];
const count = 130;

function rand(min,max){
    return Math.random()*(max-min)+min;
}

for(let i=0;i<count;i++){
    particles.push({
        x:rand(0,w),
        y:rand(0,h),
        vx:rand(-0.4,0.4),
        vy:rand(-0.4,0.4),
        r:rand(1,2)
    });
}

function animate(){
    ctx.clearRect(0,0,w,h);

    particles.forEach(p=>{
        p.x += p.vx;
        p.y += p.vy;

        if(p.x<0||p.x>w) p.vx*=-1;
        if(p.y<0||p.y>h) p.vy*=-1;

        const dx = p.x - mouse.x;
        const dy = p.y - mouse.y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        const influence = 120;

        if(dist < influence){
            const force = (1 - dist/influence) * 0.06;
            p.vx += (dx/dist) * force;
            p.vy += (dy/dist) * force;
        }
    });

    for(let i=0;i<particles.length;i++){
        for(let j=i+1;j<particles.length;j++){

            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            const maxDist = 120;

            if(dist < maxDist){
                const alpha = (1 - dist/maxDist) * 0.25;
                ctx.strokeStyle = `rgba(212,175,55,${alpha})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                ctx.stroke();
            }
        }
    }

    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle="rgba(212,175,55,0.6)";
        ctx.fill();
    });

    requestAnimationFrame(animate);
}

animate();
</script>

</body>
</html>
