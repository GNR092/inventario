<!DOCTYPE html>
    @vite(['resources/css/inventario.css','resources/css/app.css', 'resources/js/app.js',  'resources/js/inventario.js'])
</head>

<body>

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
