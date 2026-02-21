<section>

    <h2 class="text-2xl font-bold mb-3" style="color:var(--color-dorado)">
        Actualizar Contraseña
    </h2>

    <p class="mb-8" style="color:var(--color-carbon-400)">
        Asegúrate de utilizar una contraseña larga y segura.
    </p>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="block mb-2 text-sm text-gray-300">Contraseña Actual</label>
            <input type="password" name="current_password" class="input-field">
        </div>

        <div>
            <label class="block mb-2 text-sm text-gray-300">Nueva Contraseña</label>
            <input type="password" name="password" class="input-field">
        </div>

        <div>
            <label class="block mb-2 text-sm text-gray-300">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="input-field">
        </div>

        <button class="btn-gold">
            Guardar Cambios
        </button>
    </form>

</section>
