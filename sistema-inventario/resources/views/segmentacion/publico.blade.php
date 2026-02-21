<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha Segmentación #{{ $item->id }}</title>
    @vite(['resources/css/inventario.css', 'resources/js/inventario.js'])
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
            <h1 class="text-3xl font-bold"
                style="color: var(--color-dorado);">
                Ficha de Segmentación
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

    <!-- ================= INFORMACIÓN GENERAL ================= -->
    <div class="grid md:grid-cols-4 gap-4 mb-8 text-sm">

        <div class="field-box">
            <div style="{{ $label }}">Fecha</div>
            <div style="{{ $value }}">{{ $item->Fecha_AD ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Área</div>
            <div style="{{ $value }}">{{ $item->area ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Activo</div>
            <div style="{{ $value }}">{{ $item->activo ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Estado</div>
            <div style="{{ $value }}">{{ $item->estado ?? '—' }}</div>
        </div>

    </div>

    <!-- ================= FACTURACIÓN ================= -->
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

    <!-- ================= INFORMACIÓN DEL ACTIVO ================= -->
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
            <div style="{{ $label }}">Cantidad</div>
            <div style="{{ $value }}">{{ $item->Cantidad ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Número de Serie</div>
            <div style="{{ $value }}">{{ $item->Numero_Serie ?? '—' }}</div>
        </div>

    </div>

    <!-- ================= VALORES ECONÓMICOS ================= -->
    <div class="grid md:grid-cols-3 gap-4 mb-8 text-sm">

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

    </div>

    <!-- ================= OTROS DATOS ================= -->
    <div class="grid md:grid-cols-4 gap-4 mb-8 text-sm">

        <div class="md:col-span-2 field-box">
            <div style="{{ $label }}">Observación</div>
            <div style="{{ $value }}">{{ $item->Observacion ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Usuario Responsable</div>
            <div style="{{ $value }}">{{ $item->Usuario ?? '—' }}</div>
        </div>

        <div class="field-box">
            <div style="{{ $label }}">Espacio</div>
            <div style="{{ $value }}">{{ $item->Espacio ?? '—' }}</div>
        </div>

    </div>

    <!-- ================= BOTONES ================= -->
    <div class="flex gap-6 pt-6 border-t"
         style="border-color: rgba(212,175,55,.2);">

        @if($item->pdf_path)
            <a href="{{ asset('storage/'.$item->pdf_path) }}"
               target="_blank"
               class="btn-primary">
                Ver PDF
            </a>
        @endif

        @if($item->QR_Code)
            <a href="{{ route('segmentacion.qr', $item->id) }}"
               class="btn-secondary">
                Ver QR
            </a>
        @endif

    </div>

</div>
</div>

</body>
</html>
