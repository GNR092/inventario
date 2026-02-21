<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Editar Mobiliario #{{ $item->id }}
        </h2>

        <a href="{{ route('mobiliario.index') }}"
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
      action="{{ route('mobiliario.update', $item->id) }}"
      enctype="multipart/form-data"
      class="space-y-10">
    @csrf
    @method('PUT')

    <!-- ================= INFORMACIÓN GENERAL ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Información General
        </h3>

        <div class="grid md:grid-cols-4 gap-4">
            <div class="date-wrapper">
                <input type="date"
                       name="Fecha_AD"
                       value="{{ $item->Fecha_AD }}"
                       class="input-style rounded-xl px-3 py-2 w-full custom-date">
                <img src="{{ asset('icons/calendar.svg') }}" class="calendar-icon">
            </div>

            <input name="area"
                   value="{{ $item->area }}"
                   placeholder="Área"
                   class="input-style rounded-xl px-3 py-2">

            <input name="activo"
                   value="{{ $item->activo }}"
                   placeholder="Activo"
                   class="input-style rounded-xl px-3 py-2">

            <input name="estado"
                   value="{{ $item->estado }}"
                   placeholder="Estado"
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
                   value="{{ $item->No_Factura }}"
                   placeholder="No. Factura"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Folio_Externo"
                   value="{{ $item->Folio_Externo }}"
                   placeholder="Folio Externo"
                   class="input-style rounded-xl px-3 py-2">

            <input name="RFC"
                   value="{{ $item->RFC }}"
                   placeholder="RFC"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        <input name="Razon_Social"
               value="{{ $item->Razon_Social }}"
               placeholder="Razón Social"
               class="input-style rounded-xl px-3 py-2 w-full">

        <input name="Proveedor"
               value="{{ $item->Proveedor }}"
               placeholder="Proveedor"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- ================= INFORMACIÓN DEL ACTIVO ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Información del Activo
        </h3>

        <input name="Concepto"
               value="{{ $item->Concepto }}"
               placeholder="Concepto"
               class="input-style rounded-xl px-3 py-2 w-full">

        <div class="grid md:grid-cols-4 gap-4">
            <input type="number"
                   name="Cantidad"
                   value="{{ $item->Cantidad }}"
                   placeholder="Cantidad"
                   class="input-style rounded-xl px-3 py-2">

            <input type="number"
                   step="0.01"
                   name="MOI"
                   value="{{ $item->MOI }}"
                   placeholder="MOI Unitario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Usuario"
                   value="{{ $item->Usuario }}"
                   placeholder="Usuario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Numero_Serie"
                   value="{{ $item->Numero_Serie }}"
                   placeholder="Número de Serie"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        <input name="Espacio"
               value="{{ $item->Espacio }}"
               placeholder="Espacio"
               class="input-style rounded-xl px-3 py-2 w-full">

        <input name="Observacion"
               value="{{ $item->Observacion }}"
               placeholder="Observación"
               class="input-style rounded-xl px-3 py-2 w-full">
    </div>

    <!-- ================= PDF ================= -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            PDF Actual
        </h3>

        @if($item->pdf_path)
            <a href="{{ asset('storage/'.$item->pdf_path) }}"
               target="_blank"
               class="btn-secondary">
                Ver PDF Actual
            </a>
        @else
            <p class="text-sm text-gray-400">No hay PDF cargado.</p>
        @endif

        <div class="flex items-center gap-4 mt-4">
            <label for="factura"
                   class="btn-primary cursor-pointer">
                Reemplazar PDF
            </label>

            <span id="file-name"
                  class="text-sm text-gray-300">
                Ningún archivo seleccionado
            </span>

            <input type="file"
                   id="factura"
                   name="factura"
                   accept=".pdf"
                   class="hidden">
        </div>
    </div>

    <!-- ================= XML ================= -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            XML CFDI Actual
        </h3>

        @if($item->Factura_XML)
            <a href="{{ asset('storage/'.$item->Factura_XML) }}"
               target="_blank"
               class="btn-secondary">
                Ver XML Actual
            </a>
        @else
            <p class="text-sm text-gray-400">No hay XML cargado.</p>
        @endif

        <div class="flex items-center gap-4 mt-4">
            <label for="Factura_XML"
                   class="btn-secondary cursor-pointer">
                Reemplazar XML
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
    </div>

    <!-- BOTÓN -->
    <div class="pt-6">
        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold btn-primary">
            Actualizar Registro
        </button>
    </div>

</form>

</div>
</div>

<script>
document.getElementById('factura')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
    document.getElementById('file-name').textContent = fileName;
});

document.getElementById('Factura_XML')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún XML seleccionado';
    document.getElementById('xml-name').textContent = fileName;
});
</script>

</x-app-layout>
