<section>

    <h2 class="text-2xl font-bold mb-3" style="color:var(--color-dorado)">
        Información del Perfil
    </h2>

    <p class="mb-8" style="color:var(--color-carbon-400)">
        Actualiza la información de tu cuenta y tu correo electrónico.
    </p>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="block mb-2 text-sm text-gray-300">Nombre</label>
            <input type="text" name="name"
                value="{{ old('name', $user->name) }}"
                class="input-field">
        </div>

        <div>
            <label class="block mb-2 text-sm text-gray-300">Correo Electrónico</label>
            <input type="email" name="email"
                value="{{ old('email', $user->email) }}"
                class="input-field">
        </div>

        <button class="btn-gold">
            Guardar Cambios
        </button>
    </form>

</section>
