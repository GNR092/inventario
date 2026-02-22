<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Nuevo Registro de Segmentación
        </h2>

        <a href="{{ route('segmentacion.index') }}"
           class="btn-secondary">
            Volver
        </a>
    </div>
</x-slot>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="content-wrapper">
<div class="glass-card space-y-10">

{{-- ERRORES --}}
@if ($errors->any())
    <div class="p-4 rounded-xl bg-red-900">
        <ul class="list-disc ml-6 text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('segmentacion.store') }}"
      enctype="multipart/form-data"
      class="space-y-10">
@csrf

{{-- ================= INFORMACIÓN GENERAL ================= --}}
<div class="section-card space-y-6">
    <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
        Información General
    </h3>

   <div class="grid md:grid-cols-3 gap-4">

       <!-- Fecha con icono -->
       <div class="date-wrapper">
           <input type="date"
                  id="fecha"
                  name="Fecha_AD"
                  class="input-style custom-date rounded-xl px-3 py-2 w-full pr-10">

           <img src="{{ asset('icons/calendar.svg') }}"
                class="calendar-icon">
       </div>

       <!-- Usuario -->
       <input name="Usuario"
              placeholder="Usuario"
              class="input-style rounded-xl px-3 py-2">

       <!-- Área -->
       <input name="area"
              placeholder="Área"
              class="input-style rounded-xl px-3 py-2">

   </div>

    <div class="grid md:grid-cols-3 gap-4">
        <input name="estado"
               placeholder="Estado (Activo / Baja / Mantenimiento)"
               class="input-style rounded-xl px-3 py-2">

        <input name="activo"
               placeholder="Tipo de Activo"
               class="input-style rounded-xl px-3 py-2">

        <input name="Espacio"
               placeholder="Espacio"
               class="input-style rounded-xl px-3 py-2">
    </div>
</div>

{{-- ================= FACTURACIÓN ================= --}}
<div class="section-card space-y-6">
    <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
        Datos de Facturación
    </h3>

    <div class="grid md:grid-cols-3 gap-4">
        <input name="No_Factura"
               placeholder="No. Factura"
               class="input-style rounded-xl px-3 py-2">

        <input name="Folio_Externo"
               placeholder="UUID / Folio Externo"
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

{{-- ================= ACTIVO ================= --}}
<div class="section-card space-y-6">
    <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
        Información del Activo
    </h3>

    <input name="Concepto"
           placeholder="Concepto"
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
               placeholder="Número de Serie"
               class="input-style rounded-xl px-3 py-2">
    </div>

    <input name="Observacion"
           placeholder="Observación"
           class="input-style rounded-xl px-3 py-2 w-full">
</div>

{{-- ================= FACTURA PDF/XML ================= --}}
<div class="section-card space-y-4">
    <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
        Archivo de Factura (PDF)
    </h3>

    <div class="flex items-center gap-4">
        <label for="factura" class="btn-primary cursor-pointer">
            Seleccionar Archivo
        </label>

        <span id="file-name" class="text-sm text-gray-300">
            Ningún archivo seleccionado
        </span>

        <input type="file"
               id="factura"
               name="factura"
               accept=".pdf,.xml"
               class="hidden">
    </div>
</div>

{{-- ================= XML CFDI ================= --}}
<div class="section-card space-y-4">
    <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
        Cargar XML CFDI (Opcional)
    </h3>

    <div class="flex items-center gap-4">
        <label for="Factura_XML" class="btn-secondary cursor-pointer">
            Seleccionar XML
        </label>

        <span id="xml-name" class="text-sm text-gray-300">
            Ningún XML seleccionado
        </span>

        <input type="file"
               id="Factura_XML"
               name="Factura_XML"
               accept=".xml"
               class="hidden">
    </div>

    <p class="text-xs opacity-70">
        Si el XML es válido, se autocompletarán datos fiscales y el MOI.
    </p>
</div>

{{-- ================= BOTÓN ================= --}}
<div class="pt-6">
    <button type="submit"
            class="w-full py-3 rounded-xl font-semibold btn-primary">
        Guardar Registro
    </button>
</div>

</form>
</div>
</div>

{{-- ================= ESTILOS & JS ================= --}}
<style>
.custom-date{ color-scheme: dark; }
.custom-date::-webkit-calendar-picker-indicator{
    filter: invert(64%) sepia(85%) saturate(500%) hue-rotate(5deg)
            brightness(105%) contrast(105%);
}
</style>

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
for(let i=0;i<90;i++){
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

/* Nombre archivos */
document.getElementById('factura')?.addEventListener('change', e=>{
    document.getElementById('file-name').textContent =
        e.target.files[0]?.name || 'Ningún archivo seleccionado';
});
document.getElementById('Factura_XML')?.addEventListener('change', e=>{
    document.getElementById('xml-name').textContent =
        e.target.files[0]?.name || 'Ningún XML seleccionado';
});
</script>

</x-app-layout>
