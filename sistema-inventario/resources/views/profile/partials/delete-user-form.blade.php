<section class="space-y-6">

    <h2 class="text-2xl font-bold mb-3" style="color:var(--color-dorado)">
        Eliminar Cuenta
    </h2>

    <p class="mb-8" style="color:var(--color-carbon-400)">
        Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente.
        Esta acción no se puede deshacer.
    </p>

    <button
        x-data
        x-on:click.prevent="$dispatch('open-modal','confirm-user-deletion')"
        class="px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:scale-105 transition">
        Eliminar Cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post"
              action="{{ route('profile.destroy') }}"
              class="p-8 space-y-6">

            @csrf
            @method('delete')

            <h2 class="text-xl font-bold" style="color:var(--color-dorado)">
                Confirmar Eliminación
            </h2>

            <p style="color:var(--color-carbon-400)">
                Introduce tu contraseña para confirmar.
            </p>

            <input type="password"
                   name="password"
                   class="w-full p-3 rounded-xl bg-gray-900 text-white border border-gray-600">

            <x-input-error
                :messages="$errors->userDeletion->get('password')"
                class="mt-2 text-red-400" />

            <div class="flex justify-end gap-4 pt-4">

                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-6 py-2 bg-red-600 rounded-xl text-white font-bold">
                    Confirmar
                </button>

            </div>

        </form>
    </x-modal>

</section>
