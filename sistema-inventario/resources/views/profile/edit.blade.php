<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">

        <!-- Título -->
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Perfil de Usuario
        </h2>

        <!-- Botón Volver -->
        <a href="{{ url()->previous() }}"
           class="px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-300 hover:scale-105"
           style="
                background: rgba(212,175,55,.15);
                border: 1px solid rgba(212,175,55,.5);
                color: var(--color-dorado);
                backdrop-filter: blur(10px);
           ">
            ← Volver
        </a>

    </div>
</x-slot>

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

/* Fondo */
body{
    margin:0;
    overflow-x:hidden;
    background:
        radial-gradient(circle at top center,#1e293b 0%,#0f172a 40%,#0b0f14 100%);
}

/* Canvas */
#particlesCanvas{
    position:fixed;
    inset:0;
    z-index:0;
    pointer-events:none;
}

/* Halo */
.mouse-halo{
    position:fixed;
    width:600px;
    height:600px;
    border-radius:9999px;
    background: radial-gradient(circle, rgba(212,175,55,.4), rgba(212,175,55,0) 60%);
    filter:blur(100px);
    pointer-events:none;
    z-index:1;
    transform:translate(-50%,-50%);
}

/* Contenedor */
.profile-wrapper{
    position:relative;
    z-index:2;
    max-width:1000px;
    margin:80px auto;
    padding:0 30px;
}

/* Card */
.profile-card{
    background:rgba(15,23,42,.85);
    border:1px solid rgba(212,175,55,.6);
    backdrop-filter:blur(25px);
    border-radius:30px;
    padding:50px;
    margin-bottom:60px;
    box-shadow:0 30px 80px rgba(0,0,0,.6);
    transition:.4s;
}

.profile-card:hover{
    box-shadow:0 0 35px rgba(212,175,55,.4);
}

/* Inputs */
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
    box-shadow:0 0 18px rgba(212,175,55,.7);
    outline:none;
}

/* Botón dorado */
.btn-gold{
    padding:12px 30px;
    border-radius:16px;
    font-weight:700;
    background:linear-gradient(135deg,#d4af37,#f7e7a9);
    color:#111827;
    transition:.3s;
}

.btn-gold:hover{
    transform:scale(1.05);
    box-shadow:0 0 25px rgba(212,175,55,.8);
}

/* Botón rojo */
.btn-danger{
    padding:12px 30px;
    border-radius:16px;
    font-weight:700;
    background:linear-gradient(135deg,#b91c1c,#ef4444);
    color:white;
    transition:.3s;
}

.btn-danger:hover{
    transform:scale(1.05);
    box-shadow:0 0 25px rgba(239,68,68,.8);
}
</style>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="profile-wrapper">

    <!-- UPDATE PROFILE -->
    <div class="profile-card">
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- UPDATE PASSWORD -->
    <div class="profile-card">
        @include('profile.partials.update-password-form')
    </div>

    <div class="profile-card">
            @include('profile.partials.delete-user-form')
        </div>

<script>
/* Halo */
const halo=document.getElementById("mouseHalo");
window.addEventListener("mousemove",e=>{
    halo.style.left=e.clientX+"px";
    halo.style.top=e.clientY+"px";
});

/* Partículas tipo telaraña */
const canvas=document.getElementById("particlesCanvas");
const ctx=canvas.getContext("2d");
let w=canvas.width=window.innerWidth;
let h=canvas.height=window.innerHeight;

let particles=[];
for(let i=0;i<120;i++){
    particles.push({
        x:Math.random()*w,
        y:Math.random()*h,
        vx:(Math.random()-.5)*.4,
        vy:(Math.random()-.5)*.4
    });
}

function animate(){
    ctx.clearRect(0,0,w,h);

    for(let i=0;i<particles.length;i++){
        let p=particles[i];
        p.x+=p.vx;
        p.y+=p.vy;
        if(p.x<0||p.x>w)p.vx*=-1;
        if(p.y<0||p.y>h)p.vy*=-1;

        ctx.beginPath();
        ctx.arc(p.x,p.y,1.5,0,Math.PI*2);
        ctx.fillStyle="rgba(212,175,55,.7)";
        ctx.fill();

        for(let j=i+1;j<particles.length;j++){
            let p2=particles[j];
            let dx=p.x-p2.x;
            let dy=p.y-p2.y;
            let dist=Math.sqrt(dx*dx+dy*dy);
            if(dist<120){
                ctx.strokeStyle="rgba(212,175,55,"+(1-dist/120)*0.2+")";
                ctx.beginPath();
                ctx.moveTo(p.x,p.y);
                ctx.lineTo(p2.x,p2.y);
                ctx.stroke();
            }
        }
    }
    requestAnimationFrame(animate);
}
animate();
</script>

</x-app-layout>
