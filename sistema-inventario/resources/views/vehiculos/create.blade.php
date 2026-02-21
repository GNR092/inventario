<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold" style="color: var(--color-dorado);">
            Nuevo Registro de Vehículo
        </h2>

        <a href="{{ route('vehiculos.index') }}"
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

<div class="content-wrapper">
<div class="glass-card space-y-10">

@if ($errors->any())
    <div class="p-4 rounded-xl" style="background:#7d1f1f;">
        <ul class="list-disc ml-6 text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('vehiculos.store') }}"
      enctype="multipart/form-data"
      class="space-y-10">
    @csrf

    <!-- INFORMACIÓN GENERAL -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Información General
        </h3>

        <div class="grid md:grid-cols-3 gap-4">
            <div class="date-wrapper">
                <input type="date"
                       name="Fecha_AD"
                       class="input-style rounded-xl px-3 py-2 w-full custom-date">
                <img src="{{ asset('icons/calendar.svg') }}" class="calendar-icon">
            </div>

            <input name="Usuario"
                   placeholder="Usuario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Espacio"
                   placeholder="Espacio"
                   class="input-style rounded-xl px-3 py-2">
        </div>
    </div>

    <!-- FACTURACIÓN -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Datos de Facturación
        </h3>

        <div class="grid md:grid-cols-3 gap-4">
            <input name="No_Factura"
                   placeholder="No. Factura"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Folio_Externo"
                   placeholder="Folio Externo"
                   class="input-style rounded-xl px-3 py-2">

            <input name="RFC"
                   placeholder="RFC"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        <input name="Razon_Social"
               placeholder="Razón Social"
               class="input-style rounded-xl px-3 py-2 w-full">

        <input name="Proveedor"
               placeholder="Proveedor"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- INFORMACIÓN DEL VEHÍCULO -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Información del Vehículo
        </h3>

        <input name="Concepto"
               placeholder="Concepto (Marca / Modelo / Tipo)"
               class="input-style rounded-xl px-3 py-2 w-full">

        <div class="grid md:grid-cols-3 gap-4">
            <input type="number"
                   name="Cantidad"
                   placeholder="Cantidad"
                   class="input-style rounded-xl px-3 py-2">

            <input type="number"
                   step="0.01"
                   name="MOI"
                   placeholder="MOI Unitario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Numero_Serie"
                   placeholder="Número de Serie / VIN"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        <input name="Observacion"
               placeholder="Observación"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- SUBIR FACTURA PDF -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Archivo de Factura (PDF)
        </h3>

        <input type="file"
               name="factura"
               accept=".pdf"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- SUBIR XML CFDI -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Cargar XML CFDI (Autocompletar Datos)
        </h3>

        <input type="file"
               name="Factura_XML"
               accept=".xml"
               class="input-style rounded-xl px-3 py-2 w-full">

        <p class="text-xs opacity-70">
            Si el XML es válido, se completarán automáticamente RFC, Razón Social,
            Proveedor, Fecha, Concepto y MOI.
        </p>
    </div>

    <!-- BOTÓN -->
    <div class="pt-6">
        <button class="w-full py-3 rounded-xl font-semibold btn-primary">
            Guardar Vehículo
        </button>
    </div>

</form>

</div>
</div>

<!-- ESTILO CALENDARIO -->
<style>
.custom-date{
    color-scheme: dark;
}
.custom-date::-webkit-calendar-picker-indicator{
    filter: invert(64%) sepia(85%) saturate(500%) hue-rotate(5deg)
            brightness(105%) contrast(105%);
    cursor:pointer;
}
</style>

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

</x-app-layout>
