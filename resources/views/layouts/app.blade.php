<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoaventura</title>

    <!-- Bootstrap local -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

   
</head>

<body>

@if (!isset($hideNavbar) || !$hideNavbar)

<nav class="navbar navbar-expand-lg fixed-top navbar-glass">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.jpeg') }}" class="rounded-circle">
            <span class="fw-semibold text-success">Ecoaventura</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContenido">

            <!-- MENÚ -->
            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- DESTINOS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="{{ route('destinos.index') }}" data-bs-toggle="dropdown">Centros Turísticos</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('destinos.tipo','turisticos') }}">Turísticos</a></li>
                        <li><a class="dropdown-item" href="{{ route('destinos.tipo','ecoturisticos') }}">Ecoturísticos</a></li>
                        <li><a class="dropdown-item" href="{{ route('destinos.tipo','balnearios') }}">Balnearios</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cultura y Patrimonio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/ver-facebook') }}">Turismo Responsable</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/ver-facebook') }}">Rutas</a>
                </li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-login">
                    Iniciar sesión
                </a>
            </div>

        </div>
    </div>
</nav>

<!-- Espacio para navbar fijo -->
<div style="height:75px;"></div>

@endif

@if(request()->is('/'))
    <main>
        @yield('content')
    </main>
@else
    <main class="container py-4">
        @yield('content')
    </main>
@endif


<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>