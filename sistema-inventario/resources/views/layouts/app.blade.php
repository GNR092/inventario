<!DOCTYPE html>
    @vite(['resources/css/inventario.css','resources/css/app.css', 'resources/js/app.js', 'resources/js/inventario.js', 'resources/js/global_transitions.js'])
</head>

<body>

    <!-- Transición tipo Apple -->
    <div class="apple-transition" id="appleTransition" aria-hidden="true">
        <div class="veil"></div>
        <img src="{{ asset('images/Logo-MB.svg') }}" class="transition-logo" alt="Logo MB">
    </div>

    <!-- ========================= -->
    <!-- 🔝 NAV PRINCIPAL -->
    <!-- ========================= -->
    @include('layouts.navigation')


    <!-- ========================= -->
    <!-- 🧭 HEADER DE PÁGINA -->
    <!-- ========================= -->
    @isset($header)
        <header
            style="
                background-color: var(--color-carbon-900);
                border-bottom: 1px solid var(--color-carbon-800);">
            <div
                class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"
                style="color: var(--color-carbon-300);">
                {{ $header }}
            </div>
        </header>
    @endisset


    <!-- ========================= -->
    <!-- 📄 CONTENIDO PRINCIPAL -->
    <!-- ========================= -->
    <main>
        {{ $slot }}
    </main>


    <!-- ========================= -->
    <!-- 👣 FOOTER -->
    <!-- ========================= -->
    @include('layouts.footer')

</body>
</html>
