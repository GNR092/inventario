<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold" style="color: var(--color-dorado);">
            Segmentación
        </h2>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2 rounded-xl font-semibold transition hover:scale-105"
               style="background: rgba(212,175,55,.15);
                      border:1px solid rgba(212,175,55,.6);
                      color: var(--color-dorado);">
                Volver
            </a>

            <a href="{{ route('segmentacion.create') }}"
               class="px-5 py-2 rounded-xl font-semibold transition hover:scale-105"
               style="background:linear-gradient(135deg,#d4af37,#f7e7a9);
                      color:#111827;">
                Nuevo Registro
            </a>
        </div>
    </div>
</x-slot>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="mobiliario-wrapper">
<div class="mobiliario-card">

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- FILTROS -->
<form method="GET"
      action="{{ route('segmentacion.index') }}"
      class="grid md:grid-cols-5 gap-4 items-end mb-8">

    <div class="md:col-span-2">
        <label class="text-xs uppercase tracking-wider"
               style="color: var(--color-carbon-300);">
            Búsqueda General
        </label>
        <input type="text"
               name="buscar"
               value="{{ request('buscar') }}"
               placeholder="ID, Área, Concepto, Proveedor..."
               class="input-field">
    </div>

    <div>
        <label class="text-xs uppercase tracking-wider"
               style="color: var(--color-carbon-300);">
            Desde
        </label>
        <div class="date-wrapper">
            <input type="date"
                   name="fecha_inicio"
                   value="{{ request('fecha_inicio') }}"
                   class="input-field custom-date">
            <img src="{{ asset('icons/calendar.svg') }}"
                 class="calendar-icon">
        </div>
    </div>

    <div>
        <label class="text-xs uppercase tracking-wider"
               style="color: var(--color-carbon-300);">
            Hasta
        </label>
        <div class="date-wrapper">
            <input type="date"
                   name="fecha_fin"
                   value="{{ request('fecha_fin') }}"
                   class="input-field custom-date">
            <img src="{{ asset('icons/calendar.svg') }}"
                 class="calendar-icon">
        </div>
    </div>

    <div class="flex gap-3 items-end">

        <button type="submit"
                class="filter-btn"
                title="Filtrar">
            <img src="{{ asset('icons/filter.svg') }}">
        </button>

        @if(request()->hasAny(['buscar','fecha_inicio','fecha_fin']))
            <a href="{{ route('segmentacion.index') }}"
               class="filter-btn"
               title="Limpiar">
                <img src="{{ asset('icons/restart.svg') }}">
            </a>
        @endif

    </div>
</form>

<!-- TABLA -->
<div id="tableScroll"
     class="relative overflow-x-auto rounded-2xl border border-[rgba(212,175,55,.25)] cursor-grab active:cursor-grabbing">

<div style="min-width:2250px;">
<table class="w-full text-xs border-separate border-spacing-0 table-fixed">

<thead class="uppercase tracking-wider text-center bg-[#1f2937] text-[var(--color-carbon-300)]">
<tr>

<th class="px-4 py-3 w-[70px]">ID</th>
<th class="px-4 py-3 w-[120px]">Fecha</th>
<th class="px-4 py-3 w-[150px]">Área</th>
<th class="px-4 py-3 w-[150px]">Activo</th>
<th class="px-4 py-3 w-[120px]">Estado</th>
<th class="px-4 py-3 w-[180px]">Concepto</th>
<th class="px-4 py-3 w-[180px]">Razón Social</th>
<th class="px-4 py-3 w-[120px]">Factura</th>
<th class="px-4 py-3 w-[130px]">RFC</th>
<th class="px-4 py-3 w-[120px]">Folio</th>
<th class="px-4 py-3 w-[160px]">Proveedor</th>
<th class="px-4 py-3 w-[90px]">Cantidad</th>
<th class="px-4 py-3 w-[120px]">MOI</th>
<th class="px-4 py-3 w-[120px]">Valor Actual</th>
<th class="px-4 py-3 w-[140px]">MOI Total</th>
<th class="px-4 py-3 w-[130px]">Usuario</th>
<th class="px-4 py-3 w-[150px]">Serie</th>

<!-- STICKY (NO CAMBIAR TAMAÑOS) -->
<th class="w-[80px] text-center sticky right-[230px] z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)] p-0">
    PDF
</th>

<th class="w-[80px] text-center sticky right-[150px] z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)] p-0">
    QR
</th>

<th class="w-[150px] text-center sticky right-0 z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)] p-0">
    Acciones
</th>

</tr>
</thead>

<tbody class="text-center text-[var(--color-carbon-200)]">

@forelse($items as $it)
<tr class="hover:bg-[rgba(212,175,55,.05)] transition">

<td class="bg-[#0f172a] px-4 py-3">{{ $it->id }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Fecha_AD }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->area }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->activo }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->estado }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Concepto }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Razon_Social ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->No_Factura ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->RFC ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Folio_Externo ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Proveedor }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Cantidad }}</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-green-400">
    ${{ number_format($it->MOI ?? 0,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-cyan-400" title="Valor depreciado">
    ${{ number_format($it->valor_actual ?? 0,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-yellow-400">
    ${{ number_format($it->MOI_Total ?? 0,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3">{{ $it->Usuario ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Numero_Serie ?? '—' }}</td>

<!-- PDF -->
<td class="sticky right-[230px] z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)] p-0">
    <div class="flex justify-center items-center h-full py-3">
        @if($it->pdf_path)
            <a href="{{ asset('storage/'.$it->pdf_path) }}"
               target="_blank"
               class="action-btn border border-[var(--color-dorado)] text-[var(--color-dorado)]">
                <div class="logo-wrap">
                    <img src="{{ asset('icons/docs.svg') }}" class="logo">
                </div>
            </a>
        @else
            —
        @endif
    </div>
</td>

<!-- QR -->
<td class="sticky right-[150px] z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)] p-0">
    <div class="flex justify-center items-center h-full py-3">
        @if($it->QR_Code)
            <a href="{{ route('segmentacion.qr', $it->id) }}"
               class="action-btn border border-[var(--color-dorado)] text-[var(--color-dorado)]">
                <div class="logo-wrap">
                    <img src="{{ asset('icons/qr_code.svg') }}" class="logo">
                </div>
            </a>
        @else
            —
        @endif
    </div>
</td>

<!-- ACCIONES -->
<td class="sticky right-0 z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)] p-0">
    <div class="actions-wrapper h-full py-3">

        <a href="{{ route('segmentacion.publico', $it->id) }}"
           target="_blank"
           class="action-btn bg-[#064E3B] border border-[#B89230]">
            <div class="icon-wrap">
                <img src="{{ asset('icons/visibility.svg') }}">
            </div>
        </a>

        <a href="{{ route('segmentacion.edit', $it->id) }}"
           class="action-btn bg-[#7C2D12] border border-[#B89230]">
            <div class="icon-wrap">
                <img src="{{ asset('icons/edit.svg') }}">
            </div>
        </a>

        <form method="POST"
              action="{{ route('segmentacion.destroy', $it->id) }}"
              onsubmit="return confirm('¿Eliminar registro?')">
            @csrf
            @method('DELETE')
            <button class="action-btn bg-[#450A0A] border border-[#B89230]">
                <div class="icon-wrap">
                    <img src="{{ asset('icons/delete.svg') }}">
                </div>
            </button>
        </form>

    </div>
</td>

</tr>

@empty
<tr>
<td colspan="19"
    class="px-6 py-10 text-center text-[var(--color-carbon-400)]">
    No hay registros aún.
</td>
</tr>
@endforelse

</tbody>
</table>
</div>
</div>

</div>
</div>

</x-app-layout>
