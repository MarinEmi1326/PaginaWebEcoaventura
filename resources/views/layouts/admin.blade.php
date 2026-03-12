<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Ecoaventura | Panel Admin')</title>

  <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ filemtime(public_path('css/estilos.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/destinos.css') }}?v={{ filemtime(public_path('css/destinos.css')) }}">
</head>

<body>
  <div class="d-flex ea-shell">

    @if(auth()->check() && auth()->user()->rol === 'admin_destinos')
    @include('admin.partials.sidebar-destinos')
  @elseif(auth()->check() && auth()->user()->rol === 'gestor_rutas')
      @include('admin.partials.sidebar-rutas')
  @else
      @include('admin.partials.sidebar')
  @endif

  <div class="flex-grow-1 d-flex flex-column">
    @if(auth()->check() && auth()->user()->rol === 'admin_destinos')
        @include('admin.partials.topbar-destinos')
    @elseif(auth()->check() && auth()->user()->rol === 'gestor_rutas')
        @include('admin.partials.topbar-rutas')
    @else
        @include('admin.partials.topbar')
    @endif

      <main class="container-fluid py-4 px-4 px-lg-5">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>