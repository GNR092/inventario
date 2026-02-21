<x-app-layout>

    <div class="container py-4">

        <h2 class="text-white mb-4">✏️ Editar Activo</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-dark text-white shadow">
            <div class="card-body">

                <form action="{{ route('activos.update', $activo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Razón Social</label>
                        <input type="text" name="razon_social" class="form-control" value="{{ $activo->razon_social }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Departamento</label>
                        <input type="text" name="departamento" class="form-control" value="{{ $activo->departamento }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control" value="{{ $activo->area }}">
                    </div>

                    <div class="mb-3">
                        <label>Complejo</label>
                        <input type="text" name="complejo" class="form-control" value="{{ $activo->complejo }}">
                    </div>

                    <div class="mb-3">
                        <label>Categoría</label>
                        <input type="text" name="categoria" class="form-control" value="{{ $activo->categoria }}">
                    </div>

                    <div class="mb-3">
                        <label>Tipo de Aparato</label>
                        <input type="text" name="tipo_aparato" class="form-control" value="{{ $activo->tipo_aparato }}">
                    </div>

                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control" value="{{ $activo->precio }}">
                    </div>

                    <div class="mb-3">
                        <label>Depreciación</label>
                        <input type="number" step="0.01" name="depreciacion" class="form-control" value="{{ $activo->depreciacion }}">
                    </div>

                    <div class="mb-3">
                        <label>Fecha de Compra</label>
                        <input type="date" name="fecha_compra" class="form-control" value="{{ $activo->fecha_compra }}">
                    </div>

                    <div class="mb-3">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control" value="{{ $activo->numero_serie }}">
                    </div>

                    <div class="mb-3">
                        <label>Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="Activo" {{ $activo->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Mantenimiento" {{ $activo->estado == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="Baja" {{ $activo->estado == 'Baja' ? 'selected' : '' }}>Baja</option>
                            <option value="Extraviado" {{ $activo->estado == 'Extraviado' ? 'selected' : '' }}>Extraviado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Factura PDF</label>
                        <input type="file" name="factura_pdf" class="form-control">
                        @if($activo->factura_pdf)
                            <a href="{{ asset('storage/'.$activo->factura_pdf) }}" target="_blank" class="text-info">
                                Ver PDF actual
                            </a>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label>Factura XML</label>
                        <input type="file" name="factura_xml" class="form-control">
                        @if($activo->factura_xml)
                            <a href="{{ asset('storage/'.$activo->factura_xml) }}" target="_blank" class="text-info">
                                Ver XML actual
                            </a>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label>Comentarios</label>
                        <textarea name="comentarios" class="form-control" rows="3">{{ $activo->comentarios }}</textarea>
                    </div>

                    <button class="btn btn-primary w-100">Actualizar</button>

                </form>

            </div>
        </div>

    </div>

</x-app-layout>
