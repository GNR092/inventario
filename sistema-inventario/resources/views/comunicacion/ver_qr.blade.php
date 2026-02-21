<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold" style="color: var(--color-dorado);">
            Código QR del Equipo de Comunicación
        </h2>

        <a href="{{ route('comunicacion.index') }}"
           class="px-5 py-2 rounded-xl font-semibold transition hover:scale-105"
           style="background: rgba(212,175,55,.15);
                  border:1px solid rgba(212,175,55,.6);
                  color: var(--color-dorado);">
            Volver
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
    --color-carbon-200:#e5e7eb;
    --color-carbon-100:#f3f4f6;
}

body{
    margin:0;
    overflow-x:hidden;
    background:
        radial-gradient(circle at top center,#1e293b 0%,#0f172a 40%,#0b0f14 100%);
    color: var(--color-carbon-100);
}

#particlesCanvas{
    position:fixed;
    inset:0;
    z-index:0;
    pointer-events:none;
}

.mouse-halo{
    position:fixed;
    width:600px;
    height:600px;
    border-radius:9999px;
    background: radial-gradient(circle, rgba(212,175,55,.35), rgba(212,175,55,0) 60%);
    filter:blur(100px);
    pointer-events:none;
    z-index:1;
    transform:translate(-50%,-50%);
}

.qr-wrapper{
    position:relative;
    z-index:2;
    max-width:900px;
    margin:80px auto;
    padding:0 30px;
}

.qr-card{
    background: rgba(31,41,55,.15);
    border:1px solid rgba(212,175,55,.6);
    backdrop-filter:blur(2.5px);
    border-radius:30px;
    padding:50px;
    box-shadow:0 30px 80px rgba(0,0,0,.6);
    text-align:center;
}

.qr-box{
    background: var(--color-carbon-950);
    padding:30px;
    border-radius:24px;
    display:inline-block;
    animation: qrPulse 3.5s ease-in-out infinite;
    transition: .4s ease;
}

.qr-box:hover{
    animation:none;
    transform:scale(1.05);
    box-shadow: 0 0 90px rgba(212,175,55,.8);
}

@keyframes qrPulse {
    0% { box-shadow: 0 0 25px rgba(212,175,55,.25); }
    50% { box-shadow: 0 0 70px rgba(212,175,55,.55); }
    100% { box-shadow: 0 0 25px rgba(212,175,55,.25); }
}

.btn-primary{
    padding:12px 26px;
    border-radius:16px;
    font-weight:700;
    background: linear-gradient(135deg,#d4af37,#f7e7a9);
    color:#111827;
}

.btn-secondary{
    padding:12px 26px;
    border-radius:16px;
    font-weight:700;
    background: rgba(51,65,85,.75);
    color: var(--color-carbon-100);
}

.alert-danger{
    background:#7d1f1f;
    border-radius:18px;
    padding:20px;
}
</style>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="qr-wrapper">
    <div class="qr-card">

        <!-- ID -->
        <div class="mb-8">
            <p class="uppercase tracking-wider text-sm"
               style="color: var(--color-carbon-400);">
                Registro ID
            </p>
            <p class="text-4xl font-bold mt-2"
               style="color: var(--color-dorado);">
                #{{ $item->id }}
            </p>
        </div>

        @if(!empty($item->QR_Code))

            <!-- QR -->
            <div class="mb-10">
                <div class="qr-box">
                    <img
                        src="{{ asset('storage/'.$item->QR_Code) }}"
                        alt="QR"
                        class="rounded-xl max-w-full h-auto">
                </div>
            </div>

            <!-- BOTONES -->
            <div class="flex justify-center gap-6">

                <a href="{{ asset('storage/'.$item->QR_Code) }}"
                   download
                   class="btn-primary">
                    Descargar QR
                </a>

                <a href="{{ route('comunicacion.publico', $item->id) }}"
                   target="_blank"
                   class="btn-secondary">
                    Ver Ficha Pública
                </a>

            </div>

        @else

            <div class="alert-danger">
                <p class="font-semibold text-white">
                    Este equipo no tiene QR generado.
                </p>
            </div>

        @endif

    </div>
</div>

<script>
const halo=document.getElementById("mouseHalo");
window.addEventListener("mousemove",e=>{
    halo.style.left=e.clientX+"px";
    halo.style.top=e.clientY+"px";
});

const canvas=document.getElementById("particlesCanvas");
const ctx=canvas.getContext("2d");
let w=canvas.width=window.innerWidth;
let h=canvas.height=window.innerHeight;

window.addEventListener('resize',()=>{
    w=canvas.width=window.innerWidth;
    h=canvas.height=window.innerHeight;
});

let particles=[];
for(let i=0;i<100;i++){
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
        ctx.arc(p.x,p.y,1.4,0,Math.PI*2);
        ctx.fillStyle="rgba(212,175,55,.55)";
        ctx.fill();
    }
    requestAnimationFrame(animate);
}
animate();
</script>

</x-app-layout>
