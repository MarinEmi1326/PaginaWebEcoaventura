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
                                Destinos
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
                            $persona = $user->persona;
                            $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];
                            $nombreMostrar = $persona?->nombre ?? $user->correo;

                            $panelUrl = '#';
                            if (in_array('admin_general', $roles)) {
                                $panelUrl = route('admin.index');
                            } elseif (in_array('admin_destinos', $roles)) {
                                $panelUrl = route('misdestinos.index');
                            } elseif (in_array('gestor_rutas', $roles)) {
                                $panelUrl = route('rutas.index');
                            }

                            // Verificar si es turista
                            $esTurista = in_array('turista', $roles);
                        @endphp

                        <div class="dropdown">

                            <button class="btn btn-user dropdown-toggle d-flex align-items-center gap-2" type="button"
                                data-bs-toggle="dropdown">

                                {{-- 🔥 AVATAR DINÁMICO --}}
                                <div class="ea-avatar d-flex align-items-center justify-content-center overflow-hidden"
                                    style="width:25px; height:25px; border-radius:40%; background:#DFE6DE;">

                                    @if ($user->foto_perfil)
                                        <img src="{{ asset('storage/' . $user->foto_perfil) }}"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="bi bi-person-fill" style="font-size: 1.2rem; color:#1F2A24;"></i>
                                    @endif

                                </div>

                                <span>{{ $nombreMostrar }}</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="{{ route('perfil') }}">
                                        <i class="bi bi-person me-2"></i>
                                        Mi perfil
                                    </a>
                                </li>

                                {{-- Mis Viajes (solo para turistas) --}}
                                @if ($esTurista)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('turista.viajes') }}">
                                            <i class="bi bi-suitcase me-2"></i>
                                            Mis Viajes
                                        </a>
                                    </li>
                                @endif

                                {{-- Mostrar "Ir a mi panel" solo para admins y gestores (NO para turistas) --}}
                                @if (!$esTurista && !empty($roles))
                                    <li>
                                        <a class="dropdown-item" href="{{ $panelUrl }}">
                                            <i class="bi bi-speedometer2 me-2"></i>
                                            Ir a mi panel
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn btn-login-main d-flex align-items-center gap-2 px-3 py-2 shadow-sm">

                            <i class="bi bi-person"></i>
                            <span>Iniciar sesión</span>
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
    @stack('scripts')
</body>

</html>
