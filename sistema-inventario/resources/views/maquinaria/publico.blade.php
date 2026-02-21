<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de Maquinaria #{{ $item->id }}</title>
    @vite(['resources/css/inventario.css'])
</head>

<body>

@php
    $label = "color: var(--color-carbon-400); font-size: 11px; text-transform: uppercase; letter-spacing: .6px;";
    $value = "color: var(--color-carbon-100); font-weight: 700;";
@endphp

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="content-wrapper">
<div class="glass-card">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--color-dorado);">
                Ficha de Maquinaria y Equipo
            </h1>
            <p style="color: var(--color-carbon-400);">
                Registro institucional del activo
            </p>
        </div>

        <div class="text-right">
            <div class="uppercase text-xs tracking-wider"
                 style="color: var(--color-carbon-400);">
                Registro ID
            </div>
            <div class="text-3xl font-bold mt-1"
                 style="color: var(--color-dorado);">
                #{{ $item->id }}
            </div>
        </div>
    </div>

    <!-- INFORMACIÓN GENERAL -->
    <div class="grid md:grid-cols-4 gap-4 mb-8 text-sm">

        <div class="field-box">
            <div style="{{ $label }}">Fecha</div>
            <div style="{{ $value }}">{{ $item->Fecha_AD ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Usuario</div>
            <div style="{{ $value }}">{{ $item->Usuario ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Espacio</div>
            <div style="{{ $value }}">{{ $item->Espacio ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Cantidad</div>
            <div style="{{ $value }}">{{ $item->Cantidad ?? '—' }}</div>
        </div>

    </div>

    <!-- FACTURACIÓN -->
    <div class="grid md:grid-cols-4 gap-4 mb-8 text-sm">

        <div class="field-box">
            <div style="{{ $label }}">No. Factura</div>
            <div style="{{ $value }}">{{ $item->No_Factura ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Folio Externo</div>
            <div style="{{ $value }}">{{ $item->Folio_Externo ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">RFC</div>
            <div style="{{ $value }}">{{ $item->RFC ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Razón Social</div>
            <div style="{{ $value }}">{{ $item->Razon_Social ?? '—' }}</div>
        </div>

    </div>

    <!-- INFORMACIÓN DEL ACTIVO -->
    <div class="grid md:grid-cols-4 gap-4 mb-8 text-sm">

        <div class="field-box">
            <div style="{{ $label }}">Concepto</div>
            <div style="{{ $value }}">{{ $item->Concepto ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Proveedor</div>
            <div style="{{ $value }}">{{ $item->Proveedor ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">MOI Unitario</div>
            <div style="{{ $value }}">
                ${{ number_format($item->MOI ?? 0,2) }}
            </div>
        </div>

        <div class="field-box" style="border-color: var(--color-dorado); background: rgba(212,175,55,0.05);">
            <div style="{{ $label }} color: var(--color-dorado);">Valor Actual (Depreciado)</div>
            <div style="{{ $value }} color: #22d3ee; font-size: 1.1rem;">
                ${{ number_format($item->valor_actual ?? 0,2) }}
            </div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">MOI Total</div>
            <div style="{{ $value }}">
                ${{ number_format($item->MOI_Total ?? 0,2) }}
            </div>
        </div>

        <div class="md:col-span-3 field-box">
            <div style="{{ $label }}">Observación</div>
            <div style="{{ $value }}">{{ $item->Observacion ?? '—' }}</div>
        </div>

    </div>

    <!-- FACTURA PDF -->
    @if($item->pdf_path)
    <div class="pt-6 border-t"
         style="border-color: rgba(212,175,55,.2);">
        <a href="{{ asset('storage/'.$item->pdf_path) }}"
           target="_blank"
           class="btn-primary">
            Ver Factura PDF
        </a>
    </div>
    @endif

</div>
</div>

<!-- HALO + PARTÍCULAS -->
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
    for(let p of particles){
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

</body>
</html>
