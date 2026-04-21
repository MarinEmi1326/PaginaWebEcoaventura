<aside class="ea-sidebar d-flex flex-column">

    {{-- Logo --}}
    <div class="px-4 py-3 d-flex align-items-center gap-3 border-bottom" style="border-color: var(--ea-line) !important;">
        <div class="ea-logo-badge" style="background: none; padding: 0;">
            <img src="{{ asset('img/ecoaventura-logo.png') }}" alt="Ecoaventura"
                style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
        <div class="fw-semibold">Ecoaventura</div>
    </div>

    {{-- Usuario --}}
    <div class="px-4 py-3 border-bottom" style="border-color: var(--ea-line) !important;">
        @php
            $persona = auth()->user()->persona;
        @endphp
        <div class="fw-semibold">
            {{ $persona->nombre ?? 'Usuario' }} {{ $persona->apellidos ?? '' }}
        </div>
        <div class="small" style="color: var(--ea-muted);">Administrador General</div>
    </div>

    {{-- Menú --}}
    <nav class="px-3 py-3 d-grid gap-2">

        {{-- Dashboard --}}
        <a href="{{ route('admin.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i>
            <span>Dashboard</span>
        </a>

        {{-- Destinos --}}
        <a href="{{ route('admin.destino') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.aprobacion') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i>
            <span>Destinos</span>
        </a>

        {{-- Usuarios --}}
        <a href="{{ route('admin.solicitudes.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Usuarios</span>
        </a>

        {{-- Reportes --}}
        <a href="{{ route('admin.reportes') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.reportes') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data"></i>
            <span>Reportes</span>
        </a>

        {{-- Reportes de Usuarios --}}
        <a href="{{ route('admin.reportes.usuarios.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.reportes.usuarios.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Reportes de Usuarios</span>
        </a>

        {{-- Categorías --}}
        <a href="{{ route('admin.categorias.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i>
            <span>Categorías</span>
        </a>

        {{-- Actividades (catálogo) ✅ ya tiene icono --}}
        <a href="{{ route('admin.actividades.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.actividades.*') ? 'active' : '' }}">
            <i class="bi bi-activity"></i>
            <span>Actividades</span>
        </a>

        {{-- Recomendaciones (catálogo) ✅ ya tiene icono --}}
        <a href="{{ route('admin.recomendaciones.index') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.recomendaciones.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i>
            <span>Recomendaciones</span>
        </a>

        {{-- Respaldos --}}
        <a href="{{ route('admin.respaldos') }}"
            class="ea-nav-pill {{ request()->routeIs('admin.respaldos') ? 'active' : '' }}">
            <i class="bi bi-database"></i>
            <span>Respaldos</span>
        </a>

    </nav>

    {{-- Footer --}}
    <div class="mt-auto px-4 py-3 border-top" style="border-color: var(--ea-line) !important;">
        <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2"
            style="color: var(--ea-muted);">
            <i class="bi bi-house-door"></i>
            <span>Portal Público</span>
        </a>

        <form class="mt-3" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-decoration-none d-flex align-items-center gap-2"
                style="color:#d14b3a;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar Sesión</span>
            </button>
        </form>
    </div>

</aside>
