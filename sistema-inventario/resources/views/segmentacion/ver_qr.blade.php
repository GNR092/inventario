<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Código QR del Activo (Segmentación)
        </h2>

        <a href="{{ route('segmentacion.index') }}"
           class="px-5 py-2 rounded-xl font-semibold transition hover:scale-105"
           style="background: rgba(212,175,55,.15);
                  border:1px solid rgba(212,175,55,.6);
                  color: var(--color-dorado);">
            Volver
        </a>
    </div>
</x-slot>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="qr-wrapper">
<div class="qr-card">

    <!-- ID -->
    <div class="mb-8 text-center">
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
        <div class="mb-10 flex justify-center">
            <div class="qr-box">
                <img
                    src="{{ asset('storage/'.$item->QR_Code) }}"
                    alt="QR Segmentación"
                    class="rounded-xl max-w-full h-auto">
            </div>
        </div>

        <!-- BOTONES -->
        <div class="flex justify-center gap-6 flex-wrap">

            <a href="{{ asset('storage/'.$item->QR_Code) }}"
               download
               class="btn-primary">
                Descargar QR
            </a>

            <a href="{{ route('segmentacion.publico', $item->id) }}"
               target="_blank"
               class="btn-secondary">
                Ver Ficha Pública
            </a>

        </div>

    @else

        <div class="alert-danger text-center">
            <p class="font-semibold text-white">
                Este registro aún no tiene QR generado.
            </p>
        </div>

    @endif

</div>
</div>

{{-- ================= JS VISUAL ================= --}}
<script>
/* Halo */
const halo=document.getElementById("mouseHalo");
window.addEventListener("mousemove",e=>{
    halo.style.left=e.clientX+"px";
    halo.style.top=e.clientY+"px";
});

/* Partículas */
const canvas=document.getElementById("particlesCanvas");
const ctx=canvas.getContext("2d");
let w=canvas.width=window.innerWidth;
let h=canvas.height=window.innerHeight;

window.addEventListener('resize',()=>{
    w=canvas.width=window.innerWidth;
    h=canvas.height=window.innerHeight;
});

let particles=[];
for(let i=0;i<80;i++){
    particles.push({
        x:Math.random()*w,
        y:Math.random()*h,
        vx:(Math.random()-.5)*.4,
        vy:(Math.random()-.5)*.4
    });
}

(function animate(){
    ctx.clearRect(0,0,w,h);
    particles.forEach(p=>{
        p.x+=p.vx; p.y+=p.vy;
        if(p.x<0||p.x>w)p.vx*=-1;
        if(p.y<0||p.y>h)p.vy*=-1;
        ctx.beginPath();
        ctx.arc(p.x,p.y,1.4,0,Math.PI*2);
        ctx.fillStyle="rgba(212,175,55,.55)";
        ctx.fill();
    });
    requestAnimationFrame(animate);
})();
</script>

</x-app-layout>
