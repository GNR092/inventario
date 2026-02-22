<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold"
            style="color: var(--color-dorado);">
            📊 Sistema De Inventario
        </h2>

        <a href="{{ url('/') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:scale-105"
           style="background: rgba(212,175,55,.15);
                  border:1px solid rgba(212,175,55,.6);
                  color: var(--color-dorado);">
            ← Volver
        </a>
    </div>
</x-slot>

<canvas id="particlesCanvas"></canvas>
<div class="mouse-halo" id="mouseHalo"></div>

<div class="dashboard-wrapper">

    <div class="dashboard-card">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Segmentación -->
            <a href="{{ route('segmentacion.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">🗂</div>
                <div class="text-lg font-bold text-white">Segmentación</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">Gestión</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Áreas y activos institucionales
                </div>
            </a>

            <!-- Mobiliario -->
            <a href="{{ route('mobiliario.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">🪑</div>
                <div class="text-lg font-bold text-white">Mobiliario y Equipo</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">Inventario</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Inventario institucional
                </div>
            </a>

            <!-- Computo -->
            <a href="{{ route('computo.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">💻</div>
                <div class="text-lg font-bold text-white">Equipo de Cómputo</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">TI</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Computadoras y periféricos
                </div>
            </a>

            <!-- Comunicación -->
            <a href="{{ route('comunicacion.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">📡</div>
                <div class="text-lg font-bold text-white">Equipo de Comunicación</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">Redes</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Telefonía y radios
                </div>
            </a>

            <!-- Maquinaria -->
            <a href="{{ route('maquinaria.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">🏭</div>
                <div class="text-lg font-bold text-white">Maquinaria</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">Industrial</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Equipo especializado
                </div>
            </a>

            <!-- Vehículos -->
            <a href="{{ route('vehiculos.index') }}" class="inv-card relative">
                <div class="text-xl mb-2">🚗</div>
                <div class="text-lg font-bold text-white">Vehículos</div>
                <span class="absolute top-[30px] right-[20px] text-xs uppercase px-2 py-0.5 rounded-full bg-[var(--color-dorado)] text-black">Transporte</span>
                <div class="text-sm mt-1" style="color:var(--color-carbon-400)">
                    Parque vehicular
                </div>
            </a>
</div>
</x-app-layout>
