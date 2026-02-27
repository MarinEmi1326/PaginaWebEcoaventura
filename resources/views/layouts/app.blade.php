<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoaventura</title>


    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Tus estilos -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ filemtime(public_path('css/estilos.css')) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap  icons local -->
    <link rel="stylesheet" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">


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

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContenido">
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
                            <a class="nav-link dropdown-toggle" href="{{ route('destinos.index') }}"
                                data-bs-toggle="dropdown">
                                Destinos
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('destinos.tipo', 'turisticos') }}">Turísticos</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destinos.tipo', 'ecoturisticos') }}">Ecoturísticos</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destinos.tipo', 'balnearios') }}">Balnearios</a></li>
                            </ul>
                        </li>

                        <!-- SERVICIOS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('servicios.index') }}"
                                data-bs-toggle="dropdown">
                                Servicios
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('servicios.tipo', 'hospedaje') }}">Hospedaje</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('servicios.tipo', 'restaurantes') }}">Restaurantes</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Nosotros</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/ver-facebook') }}">Contacto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mapa') }}">Mapa</a>
                        </li>

                    </ul>

                    <!-- BOTONES -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-login">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register text-white">
                            Registrarse
                        </a>
                    </div>


                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/ver-facebook') }}">Turismo Responsable</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/ver-facebook') }}">Rutas</a>
                </li>
            </ul>
            <a href="{{ route('login') }}" class="btn btn-success rounded-pill">
    Iniciar sesión
</a>


                </div>
            </div>
        </nav>


        <!-- Espacio para navbar fijo -->
        <div style="height:75px;"></div>
    @endif


                    <!-- BOTONES -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-login">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register text-white">
                            Registrarse
                        </a>
                    </div>

                   </li>
               
                <a class="nav-link {{ request()->routeIs('cultura') ? 'active' : '' }}"
                    href="{{ route('cultura') }}">
                        Cultura y Patrimonio
                </a>
                 </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/ver-facebook') }}">Turismo Responsable</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/ver-facebook') }}">Rutas</a>
                </li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-success rounded-pill">
                    Iniciar sesión
                </a>


                </div>
            </div>
        </nav>


<!-- Espacio para navbar fijo -->
<div style="height:75px;"></div>


    @if (request()->is('/'))
        <main>
            @yield('content')
        </main>
    @else
        <main class="container py-4">
            @yield('content')
        </main>
    @endif

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

@if(request()->is('/'))
    <main>
        @yield('content')
    </main>
@else
    <main class="container py-4">
        @yield('content')
    </main>
@endif



@include('layouts.footer')
<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
