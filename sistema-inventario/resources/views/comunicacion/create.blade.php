<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Nuevo Registro de Equipo de Comunicación
        </h2>

        <a href="{{ route('comunicacion.index') }}"
           class="btn-secondary">
            Volver
        </a>
    </div>
</x-slot>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="content-wrapper">
<div class="glass-card space-y-10">

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
      action="{{ route('comunicacion.store') }}"
      enctype="multipart/form-data"
      class="space-y-10">
    @csrf

    <!-- ================= INFORMACIÓN GENERAL ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Información General
        </h3>

        <div class="grid md:grid-cols-3 gap-4">

               <!-- Fecha con icono -->
               <div class="date-wrapper">
                   <input type="date"
                          id="fecha"
                          name="Fecha_AD"
                          class="input-style custom-date rounded-xl px-3 py-2 w-full">

                   <img src="{{ asset('icons/calendar.svg') }}"
                        class="calendar-icon">
               </div>

            <input name="Usuario"
                   placeholder="Usuario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Espacio"
                   placeholder="Espacio"
                   class="input-style rounded-xl px-3 py-2">
        </div>
    </div>

    <!-- ================= FACTURACIÓN ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
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

    <!-- ================= EQUIPO ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Información del Equipo
        </h3>

        <input name="Concepto"
               placeholder="Concepto (Ej. Radio portátil, Switch, Antena)"
               class="input-style rounded-xl px-3 py-2 w-full">

        <div class="grid md:grid-cols-3 gap-4">
            <input type="number"
                   name="cantidad"
                   placeholder="Cantidad"
                   class="input-style rounded-xl px-3 py-2">

            <input type="number"
                   step="0.01"
                   name="MOI"
                   placeholder="MOI Unitario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Observacion"
                   placeholder="Observación"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        <input name="Numero_Serie"
               placeholder="Número de Serie"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- ================= FACTURA PDF/XML ================= -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Archivo de Factura
        </h3>

        <div class="flex items-center gap-4">
            <label for="factura"
                   class="btn-primary cursor-pointer">
                Seleccionar Archivo
            </label>

            <span id="file-name"
                  class="text-sm text-gray-300">
                Ningún archivo seleccionado
            </span>

            <input type="file"
                   id="factura"
                   name="factura"
                   accept=".pdf,.xml"
                   class="hidden">
        </div>
    </div>

    <!-- ================= XML CFDI ================= -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Cargar XML CFDI (Autocompletar Datos)
        </h3>

        <div class="flex items-center gap-4">
            <label for="Factura_XML"
                   class="btn-secondary cursor-pointer">
                Seleccionar XML CFDI
            </label>

            <span id="xml-name"
                  class="text-sm text-gray-300">
                Ningún XML seleccionado
            </span>

            <input type="file"
                   id="Factura_XML"
                   name="Factura_XML"
                   accept=".xml"
                   class="hidden">
        </div>

        <p class="text-xs text-gray-400">
            Si el XML es válido, se llenarán automáticamente RFC, Razón Social,
            Proveedor, Fecha, Concepto y MOI.
        </p>
    </div>

    <!-- ================= BOTÓN ================= -->
    <div class="pt-6">
        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold btn-primary">
            Guardar Equipo de Comunicación
        </button>
    </div>

</form>

</div>
</div>

<script>
document.getElementById('Factura_XML')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún XML seleccionado';
    document.getElementById('xml-name').textContent = fileName;
});

document.getElementById('factura')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
    document.getElementById('file-name').textContent = fileName;
});
</script>

</x-app-layout>
