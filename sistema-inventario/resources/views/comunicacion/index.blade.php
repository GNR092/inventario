<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold" style="color: var(--color-dorado);">
            Equipo de Comunicación
        </h2>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2 rounded-xl font-semibold transition hover:scale-105"
               style="background: rgba(212,175,55,.15);
                      border:1px solid rgba(212,175,55,.6);
                      color: var(--color-dorado);">
                Volver
            </a>

            <a href="{{ route('comunicacion.create') }}"
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
      action="{{ route('comunicacion.index') }}"
      class="grid md:grid-cols-5 gap-4 items-end mb-8">

    <div class="md:col-span-2">
        <label class="text-xs uppercase tracking-wider" style="color: var(--color-carbon-300);">
            Búsqueda General
        </label>
        <input type="text"
               name="buscar"
               value="{{ request('buscar') }}"
               placeholder="ID, Razón Social, RFC, Concepto..."
               class="input-field">
    </div>

    <div>
        <label class="text-xs uppercase tracking-wider" style="color: var(--color-carbon-300);">
            Desde
        </label>
        <div class="date-wrapper">
            <input type="date"
                   name="fecha_inicio"
                   value="{{ request('fecha_inicio') }}"
                   class="input-field custom-date">
            <img src="{{ asset('icons/calendar.svg') }}" class="calendar-icon">
        </div>
    </div>

    <div>
        <label class="text-xs uppercase tracking-wider" style="color: var(--color-carbon-300);">
            Hasta
        </label>
        <div class="date-wrapper">
            <input type="date"
                   name="fecha_fin"
                   value="{{ request('fecha_fin') }}"
                   class="input-field custom-date">
            <img src="{{ asset('icons/calendar.svg') }}" class="calendar-icon">
        </div>
    </div>

    <div class="flex gap-3 items-end">
        <button type="submit" class="filter-btn">
            <img src="{{ asset('icons/filter.svg') }}">
        </button>

        @if(request()->hasAny(['buscar','fecha_inicio','fecha_fin']))
            <a href="{{ route('comunicacion.index') }}" class="filter-btn">
                <img src="{{ asset('icons/restart.svg') }}">
            </a>
        @endif
    </div>
</form>

<div id="tableScroll"
     class="relative overflow-x-auto rounded-2xl border border-[rgba(212,175,55,.25)] cursor-grab active:cursor-grabbing">

<div style="min-width:2150px;">
<table class="w-full text-xs border-separate border-spacing-0 table-fixed">

<thead class="uppercase tracking-wider text-center bg-[#1f2937] text-[var(--color-carbon-300)]">
<tr>
<th class="px-4 py-3 w-[70px]">ID</th>
<th class="px-4 py-3 w-[120px]">Fecha</th>
<th class="px-4 py-3 w-[180px]">Concepto</th>
<th class="px-4 py-3 w-[180px]">Razón Social</th>
<th class="px-4 py-3 w-[120px]">No. Factura</th>
<th class="px-4 py-3 w-[130px]">RFC</th>
<th class="px-4 py-3 w-[120px]">Folio</th>
<th class="px-4 py-3 w-[160px]">Proveedor</th>
<th class="px-4 py-3 w-[90px]">Cantidad</th>
<th class="px-4 py-3 w-[130px]">Espacio</th>
<th class="px-4 py-3 w-[160px]">No. Serie</th>
<th class="px-4 py-3 w-[180px]">Observación</th>
<th class="px-4 py-3 w-[120px]">MOI</th>
<th class="px-4 py-3 w-[120px]">Valor Actual</th>
<th class="px-4 py-3 w-[140px]">MOI Total</th>
<th class="px-4 py-3 w-[130px]">Usuario</th>

<th class="w-[80px] text-center sticky right-[230px] z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)]">Factura</th>
<th class="w-[80px] text-center sticky right-[150px] z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)]">QR</th>
<th class="w-[150px] text-center sticky right-0 z-50 bg-[#1f2937] border-l border-[rgba(212,175,55,.18)]">Acciones</th>
</tr>
</thead>

<tbody class="text-center text-[var(--color-carbon-200)]">

@forelse($items as $it)
<tr class="hover:bg-[rgba(212,175,55,.05)] transition">

<td class="bg-[#0f172a] px-4 py-3">{{ $it->id }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Fecha_AD }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Concepto }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Razon_Social ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->No_Factura ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->RFC ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Folio_Externo ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Proveedor }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->cantidad }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Espacio }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Numero_Serie ?? '—' }}</td>
<td class="bg-[#0f172a] px-4 py-3">{{ $it->Observacion ?? '—' }}</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-green-400">
${{ number_format($it->MOI,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-cyan-400">
${{ number_format($it->valor_actual,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3 font-semibold text-yellow-400">
${{ number_format($it->MOI_Total,2) }}
</td>

<td class="bg-[#0f172a] px-4 py-3">{{ $it->Usuario ?? '—' }}</td>

<!-- FACTURA -->
<td class="sticky right-[230px] z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)]">
@if($it->pdf_path)
<a href="{{ asset('storage/'.$it->factura_path) }}" target="_blank" class="action-btn border border-[var(--color-dorado)] text-[var(--color-dorado)]">
<img src="{{ asset('icons/docs.svg') }}" class="logo">
</a>
@else — @endif
</td>

<!-- QR -->
<td class="sticky right-[150px] z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)]">
@if($it->QR_Code)
<a href="{{ route('comunicacion.qr',$it->id) }}" class="action-btn border border-[var(--color-dorado)] text-[var(--color-dorado)]">
<img src="{{ asset('icons/qr_code.svg') }}" class="logo">
</a>
@else — @endif
</td>

<!-- ACCIONES -->
<td class="sticky right-0 z-40 bg-[#0f172a] border-l border-[rgba(212,175,55,.18)]">
<div class="actions-wrapper h-full py-3">

<a href="{{ route('comunicacion.publico',$it->id) }}" target="_blank"
   class="action-btn bg-[#064E3B] border border-[#B89230]">
<img src="{{ asset('icons/visibility.svg') }}">
</a>

<a href="{{ route('comunicacion.edit',$it->id) }}"
   class="action-btn bg-[#7C2D12] border border-[#B89230]">
<img src="{{ asset('icons/edit.svg') }}">
</a>

<form method="POST"
      action="{{ route('comunicacion.destroy',$it->id) }}"
      onsubmit="return confirm('¿Eliminar registro?')">
@csrf
@method('DELETE')
<button class="action-btn bg-[#450A0A] border border-[#B89230]">
<img src="{{ asset('icons/delete.svg') }}">
</button>
</form>

</div>
</td>

</tr>

@empty
<tr>
<td colspan="18" class="px-6 py-10 text-center text-[var(--color-carbon-400)]">
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
