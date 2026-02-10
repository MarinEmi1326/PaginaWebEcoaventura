<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Hotelero') - Ecoaventura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f4ee] text-slate-900">
    <div class="min-h-screen">
        <div class="flex">

            {{-- ✅ Sidebar unificado --}}
            @include('hotelero.partials.sidebar')

            {{-- ✅ Contenido (empujado por el sidebar fijo w-72) --}}
            <main class="flex-1 overflow-x-hidden" style="margin-left: 18rem;">
                {{-- aquí puedes meter header común si quieres después --}}
                <div class="p-8">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>
</body>
</html>
