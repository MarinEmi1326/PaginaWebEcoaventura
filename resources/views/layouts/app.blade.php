<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoaventura</title>

    <!-- Bootstrap local -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap icons local -->
    <link rel="stylesheet" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">

    @stack('styles')
</head>

<body>

    @if (!isset($hideNavbar) || !$hideNavbar)
        <nav class="navbar navbar-expand-lg fixed-top navbar-glass">
            <div class="container">

                <!-- LOGO -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="{{ asset('img/ecoaventura-logo.png') }}" class="rounded-circle" alt="Ecoaventura Logo">
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
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                                Inicio
                            </a>
                        </li>

                        <!-- DESTINOS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('destinos.index') }}"
                                data-bs-toggle="dropdown">
                                Centros Turísticos
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('destinos.index') }}">Ver todos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('destinos.index') }}?cat=1">Turísticos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('destinos.index') }}?cat=2">Ecoturísticos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('destinos.index') }}?cat=3">Balnearios</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('cultura') ? 'active' : '' }}"
                                href="{{ url('cultura') }}">
                                Cultura y Patrimonio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('turismo-responsable') ? 'active' : '' }}"
                                href="{{ url('turismo-responsable') }}">
                                Turismo Responsable
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('ruta') ? 'active' : '' }}"
                                href="{{ url('ruta') }}">
                                Rutas
                            </a>
                        </li>

                    </ul>

                    @auth

                        @php
                            $user = auth()->user();
                            $nombre = $user->correo;
                            $panel = '/';

                            if ($user->rol == 'turista' && $user->turista) {
                                $nombre = $user->turista->nombre;
                                $panel = route('perfil');
                            } elseif ($user->rol == 'admin_general' && $user->adminGeneral) {
                                $nombre = $user->adminGeneral->nombre;
                                $panel = route('admin.index');
                            } elseif ($user->rol == 'admin_destinos' && $user->adminDestinos) {
                                $nombre = $user->adminDestinos->nombre;
                                $panel = route('misdestinos.index');
                            } elseif ($user->rol == 'gestor_rutas' && $user->gestorRutas) {
                                $nombre = $user->gestorRutas->nombre;
                                $panel = route('rutas.index');
                            }
                        @endphp

                        <div class="dropdown">

                            <button class="btn btn-success rounded-pill dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $nombre }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="{{ $panel }}">
                                        <i class="bi bi-speedometer2 me-1"></i>
                                        Ir a mi panel
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i>
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success rounded-pill">
                            Iniciar sesión
                        </a>

                    @endauth

                </div>
            </div>
        </nav>

        <!-- Espacio para navbar fijo -->
        <div style="height:75px;"></div>
    @endif


    @if (request()->is('/'))
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
