<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- TÍTULO -->
            <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg mb-6">
                <h1 class="text-3xl font-bold">📦 Inventario de Activos</h1>
                <p class="text-gray-300 mt-2">Administración de inmuebles y aparatos electrónicos</p>
            </div>

            <!-- BOTÓN AGREGAR -->
            <div class="flex justify-end mb-5">
                <a href="{{ route('activos.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 rounded-lg shadow-md transition">
                    ➕ Agregar Activo
                </a>
            </div>

            <!-- FILTROS -->
            <div class="bg-gray-800 text-white p-5 rounded-xl shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">🔍 Filtros</h2>

                <form method="GET" action="{{ route('activos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- MES -->
                    <div>
                        <label class="block text-sm mb-1">Mes de compra</label>
                        <select name="mes" class="w-full rounded-lg bg-gray-900 text-white border-gray-700">
                            <option value="">-- Todos --</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- AÑO -->
                    <div>
                        <label class="block text-sm mb-1">Año de compra</label>
                        <input type="number" name="anio" value="{{ request('anio') }}"
                            class="w-full rounded-lg bg-gray-900 text-white border-gray-700"
                            placeholder="Ej: 2025">
                    </div>

                    <!-- ESTADO -->
                    <div>
                        <label class="block text-sm mb-1">Estado</label>
                        <select name="estado" class="w-full rounded-lg bg-gray-900 text-white border-gray-700">
                            <option value="">-- Todos --</option>
                            <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Mantenimiento" {{ request('estado') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="Baja" {{ request('estado') == 'Baja' ? 'selected' : '' }}>Baja</option>
                            <option value="Extraviado" {{ request('estado') == 'Extraviado' ? 'selected' : '' }}>Extraviado</option>
                        </select>
                    </div>

                    <!-- BOTÓN -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg shadow transition">
                            Aplicar
                        </button>
                    </div>

                </form>
            </div>

            <!-- TABLA -->
            <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg overflow-x-auto">
                <h2 class="text-2xl font-bold mb-4">📋 Lista de Activos</h2>

                <table class="min-w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-gray-200">
                            <th class="p-3">ID</th>
                            <th class="p-3">Razón Social</th>
                            <th class="p-3">Departamento</th>
                            <th class="p-3">Área</th>
                            <th class="p-3">Complejo</th>
                            <th class="p-3">Categoría</th>
                            <th class="p-3">Aparato</th>
                            <th class="p-3">Serie</th>
                            <th class="p-3">Precio</th>
                            <th class="p-3">Fecha</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($activos as $activo)
                            <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                                <td class="p-3">{{ $activo->id }}</td>
                                <td class="p-3">{{ $activo->razon_social }}</td>
                                <td class="p-3">{{ $activo->departamento }}</td>
                                <td class="p-3">{{ $activo->area }}</td>
                                <td class="p-3">{{ $activo->complejo }}</td>
                                <td class="p-3">{{ $activo->categoria }}</td>
                                <td class="p-3">{{ $activo->tipo_aparato }}</td>
                                <td class="p-3">{{ $activo->numero_serie }}</td>
                                <td class="p-3">${{ number_format($activo->precio, 2) }}</td>
                                <td class="p-3">{{ $activo->fecha_compra }}</td>

                                <td class="p-3">
                                    @if($activo->estado == "Activo")
                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">Activo</span>
                                    @elseif($activo->estado == "Mantenimiento")
                                        <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold">Mantenimiento</span>
                                    @elseif($activo->estado == "Baja")
                                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">Baja</span>
                                    @else
                                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">Extraviado</span>
                                    @endif
                                </td>

                                <td class="p-3 text-center flex gap-2 justify-center">

                                    <a href="{{ route('activos.show', $activo->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-white font-bold">
                                        Ver
                                    </a>

                                    <a href="{{ route('activos.edit', $activo->id) }}"
                                        class="bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded-lg text-white font-bold">
                                        Editar
                                    </a>

                                    <form action="{{ route('activos.destroy', $activo->id) }}" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este activo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-white font-bold">
                                            Eliminar
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="p-5 text-center text-gray-400">
                                    No hay activos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- PAGINACIÓN -->
                <div class="mt-6">
                    {{ $activos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
