<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de Equipos de Cómputo</title>
    @vite(['resources/css/inventario.css', 'resources/js/inventario.js'])
    <style>
        .product-card {
            background: rgba(31, 41, 55, 0.25);
            border: 1px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .product-card:hover {
            transform: translateY(-10px);
            border-color: var(--color-dorado);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(212, 175, 55, 0.2);
        }
        .product-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        .price-tag {
            background: linear-gradient(135deg, #d4af37, #f7e7a9);
            color: #111827;
            padding: 6px 16px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.25rem;
            display: inline-block;
        }
        .specs-box {
            background: rgba(11, 15, 20, 0.6);
            border-radius: 16px;
            padding: 12px;
            font-size: 0.85rem;
            color: var(--color-carbon-300);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body>

    <canvas id="particlesCanvas"></canvas>
    <div class="mouse-halo" id="mouseHalo"></div>

    <div class="container mx-auto px-6 py-16 relative z-10">

        <!-- HEADER -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold mb-4" style="color: var(--color-dorado); text-shadow: 0 0 20px rgba(212,175,55,0.3);">
                Catálogo de Equipos
            </h1>
            <p class="text-xl opacity-70 max-w-2xl mx-auto">
                Explora nuestra selección portatiles disponibles.
            </p>
            <div class="w-24 h-1 bg-[var(--color-dorado)] mx-auto mt-6 rounded-full"></div>
        </div>

        <!-- GRID DE PRODUCTOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($equipos as $eq)
                <div class="product-card flex flex-col">

                    <!-- FOTO -->
                    @if($eq->foto_path)
                        <img src="{{ asset('storage/'.$eq->foto_path) }}" class="product-image" alt="{{ $eq->Concepto }}">
                    @else
                        <div class="product-image flex items-center justify-center bg-[#0b0f14] text-[var(--color-dorado)] opacity-40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <!-- CONTENIDO -->
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="mb-4">
                            <span class="text-[10px] uppercase tracking-widest text-[var(--color-dorado)] font-bold opacity-80">
                                ID Registro #{{ $eq->id }}
                            </span>
                            <h2 class="text-2xl font-bold mt-1 leading-tight text-white">
                                {{ $eq->Concepto }}
                            </h2>
                        </div>

                        <!-- ESPECIFICACIONES -->
                        @if($eq->especificaciones)
                            <div class="specs-box mb-6 italic">
                                "{{ $eq->especificaciones }}"
                            </div>
                        @endif

                        <div class="mt-auto">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">Precio Actual</div>
                                    <div class="price-tag">
                                        ${{ number_format($eq->valor_actual, 2) }}
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-black bg-opacity-20 rounded-3xl border border-dashed border-gray-700">
                    <p class="text-gray-500 text-lg italic">No hay equipos publicados en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>
