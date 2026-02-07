<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ecoaventura | Panel Admin')</title>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">
    <div class="flex min-h-screen bg-slate-50">

        {{-- SIDEBAR (reutilizable) --}}
        @include('admin.partials.sidebar')

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col">

            {{-- TOPBAR (reutilizable) --}}
            @include('admin.partials.topbar')

            {{-- CONTENIDO DE CADA VISTA --}}
            <main class="p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>
