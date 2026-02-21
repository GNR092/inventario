<nav style="background-color: var(--color-carbon-900);
            border-bottom: 1px solid var(--color-carbon-800);">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- IZQUIERDA -->
            <div class="flex items-center">

                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="/images/Logo-MB.svg"
                         class="h-10 w-auto transition duration-300 ease-in-out hover:scale-105 hover:brightness-110">

                </a>

                <div class="hidden sm:flex sm:ms-10">
                    <a href="{{ route('dashboard') }}"
                       class="relative text-sm font-bold tracking-wide transition duration-300 ease-in-out group
                              hover:drop-shadow-[0_0_10px_rgba(212,175,55,0.7)]"
                       style="color: var(--color-dorado);">

                        MB Signature Properties

                        <!-- Línea dorada animada -->
                        <span class="absolute left-0 -bottom-1 h-[2px] w-0
                                     transition-all duration-300 ease-in-out group-hover:w-full"
                              style="background: linear-gradient(to right, #d4af37, #f5d76e);">
                        </span>

                    </a>
                </div>

            </div>

            <!-- DERECHA -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">

                    <!-- BOTÓN USUARIO -->
                    <button id="userButton"
                            type="button"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition hover:scale-105"
                            style="background-color: var(--color-carbon-800);
                                   color: var(--color-carbon-300);">

                        {{ Auth::user()->name }}

                        <svg id="arrowIcon"
                             class="ms-2 h-4 w-4 transition-transform duration-200"
                             fill="currentColor"
                             viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- DROPDOWN -->
                    <div id="userDropdown"
                         class="absolute right-0 mt-2 w-44 rounded-xl shadow-xl overflow-hidden
                                opacity-0 scale-95 translate-y-2 pointer-events-none
                                transition-all duration-200 ease-out"
                         style="background-color: var(--color-carbon-900);
                                border: 1px solid var(--color-carbon-800);">

                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-3 text-sm transition"
                           style="color: var(--color-carbon-300);">
                            Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 text-sm transition"
                                    style="color:#ff6b6b;">
                                Cerrar sesión
                            </button>
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

</nav>

<!-- SCRIPT FUERA DEL NAV -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const button = document.getElementById('userButton');
    const dropdown = document.getElementById('userDropdown');
    const arrow = document.getElementById('arrowIcon');

    if (!button || !dropdown) return;

    button.addEventListener('click', function (e) {
        e.stopPropagation();

        const isOpen = dropdown.classList.contains('opacity-100');

        if (isOpen) {
            dropdown.classList.remove('opacity-100','scale-100','translate-y-0','pointer-events-auto');
            dropdown.classList.add('opacity-0','scale-95','translate-y-2','pointer-events-none');
            arrow.style.transform = "rotate(0deg)";
        } else {
            dropdown.classList.remove('opacity-0','scale-95','translate-y-2','pointer-events-none');
            dropdown.classList.add('opacity-100','scale-100','translate-y-0','pointer-events-auto');
            arrow.style.transform = "rotate(180deg)";
        }
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('opacity-100','scale-100','translate-y-0','pointer-events-auto');
        dropdown.classList.add('opacity-0','scale-95','translate-y-2','pointer-events-none');
        arrow.style.transform = "rotate(0deg)";
    });

});
</script>
