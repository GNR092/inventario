<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            Editar Equipo de Cómputo
        </h2>

        <a href="{{ route('computo.index') }}"
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
    <div class="p-4 rounded-xl" style="background:#7d1f1f;">
        <ul class="list-disc ml-6 text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('computo.update', $item->id) }}"
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

        <div class="grid md:grid-cols-3 gap-4">
            <div class="date-wrapper">
                <input type="date"
                       name="Fecha_AD"
                       value="{{ $item->Fecha_AD }}"
                       class="input-style rounded-xl px-3 py-2 w-full custom-date">
                <img src="{{ asset('icons/calendar.svg') }}" class="calendar-icon">
            </div>

            <input name="Usuario"
                   value="{{ $item->Usuario }}"
                   placeholder="Usuario"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Espacio"
                   value="{{ $item->Espacio }}"
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

    <!-- ================= INFORMACIÓN DEL EQUIPO ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Información del Equipo
        </h3>

        <input name="Concepto"
               value="{{ $item->Concepto }}"
               placeholder="Concepto"
               class="input-style rounded-xl px-3 py-2 w-full">

        <div class="grid md:grid-cols-3 gap-4">
            <input type="number"
                   name="Cantidad"
                   value="{{ $item->Cantidad }}"
                   placeholder="Cantidad"
                   class="input-style rounded-xl px-3 py-2">

            <input type="number"
                   step="0.01"
                   name="MOI"
                   value="{{ $item->MOI }}"
                   placeholder="MOI"
                   class="input-style rounded-xl px-3 py-2">

            <input name="Observacion"
                   value="{{ $item->Observacion }}"
                   placeholder="Observación"
                   class="input-style rounded-xl px-3 py-2">
        </div>

        {{-- Mostrar MOI Total (solo visual, lo calcula MySQL) --}}
        <div class="mt-4 p-4 rounded-xl"
             style="background: rgba(212,175,55,.08);
                    border:1px solid rgba(212,175,55,.3);">

            <div class="text-xs uppercase tracking-wider"
                 style="color: var(--color-carbon-400);">
                Total Calculado
            </div>

            <div class="text-lg font-bold"
                 style="color: var(--color-dorado);">
                ${{ number_format($item->MOI_Total ?? 0, 2) }}
            </div>
        </div>
    </div>

    <!-- ================= ARCHIVO ================= -->
    <div class="section-card space-y-4">
        <h3 class="text-lg font-semibold"
            style="color: var(--color-dorado);">
            Reemplazar Factura
        </h3>

        <div class="flex items-center gap-4">
            <label for="factura"
                   class="btn-primary cursor-pointer">
                Seleccionar Archivo
            </label>

            <span id="file-name"
                  class="text-sm"
                  style="color: var(--color-carbon-300);">
                Ningún archivo seleccionado
            </span>

            <input type="file"
                   id="factura"
                   name="factura"
                   accept=".pdf,.xml"
                   class="hidden">
        </div>
    </div>

    <!-- ================= CONFIGURACIÓN CATÁLOGO PÚBLICO ================= -->
    <div class="section-card space-y-6">
        <h3 class="text-lg font-semibold" style="color: var(--color-dorado);">
            Exhibición en Catálogo Público (Opcional)
        </h3>

        <div class="flex items-center gap-3 mb-4">
            <input type="hidden" name="publicado" value="0">
            <input type="checkbox" name="publicado" value="1" id="publicado"
                   {{ $item->publicado ? 'checked' : '' }}
                   class="rounded bg-[#0b0f14] border-[rgba(212,175,55,.4)] text-[var(--color-dorado)] focus:ring-[var(--color-dorado)]">
            <label for="publicado" class="text-sm font-medium text-gray-300">
                Publicar este equipo en el catálogo público
            </label>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase tracking-wider mb-2" style="color: var(--color-carbon-400);">
                    Foto del Equipo
                </label>
                
                @if($item->foto_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$item->foto_path) }}" 
                             class="w-32 h-32 object-cover rounded-lg border border-[var(--color-dorado)]">
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <label for="foto" class="btn-secondary cursor-pointer py-2 text-sm">
                        {{ $item->foto_path ? 'Cambiar Foto' : 'Subir Foto' }}
                    </label>
                    <span id="foto-name" class="text-xs text-gray-400">Sin archivo nuevo</span>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider mb-2" style="color: var(--color-carbon-400);">
                    Especificaciones Técnicas
                </label>
                <textarea name="especificaciones" rows="4" 
                          placeholder="Ej: Procesador i7, 16GB RAM, 512GB SSD..."
                          class="input-style rounded-xl px-3 py-2 w-full text-sm">{{ $item->especificaciones }}</textarea>
            </div>
        </div>
    </div>

    <!-- ================= BOTÓN ================= -->
    <div class="pt-6">
        <button class="w-full py-3 rounded-xl font-semibold btn-primary">
            Guardar Cambios
        </button>
    </div>

</form>

</div>
</div>

<script>
document.getElementById('foto')?.addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Sin archivo nuevo';
    document.getElementById('foto-name').textContent = fileName;
});

document.getElementById('factura').addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
    document.getElementById('file-name').textContent = fileName;
});
</script>

</x-app-layout>
