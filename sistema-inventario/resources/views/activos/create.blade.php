<x-app-layout>

    <div class="container py-4">

        <h2 class="text-white mb-4">➕ Registrar Activo</h2>

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

                <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>Razón Social</label>
                        <input type="text" name="razon_social" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Departamento</label>
                        <input type="text" name="departamento" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Complejo</label>
                        <input type="text" name="complejo" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Categoría</label>
                        <input type="text" name="categoria" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Tipo de Aparato</label>
                        <input type="text" name="tipo_aparato" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Depreciación</label>
                        <input type="number" step="0.01" name="depreciacion" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Fecha de Compra</label>
                        <input type="date" name="fecha_compra" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="Activo">Activo</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Baja">Baja</option>
                            <option value="Extraviado">Extraviado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Factura PDF</label>
                        <input type="file" name="factura_pdf" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Factura XML</label>
                        <input type="file" name="factura_xml" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Comentarios</label>
                        <textarea name="comentarios" class="form-control" rows="3"></textarea>
                    </div>

                    <button class="btn btn-success w-100">Guardar Activo</button>

                </form>

            </div>
        </div>

    </div>

</x-app-layout>
