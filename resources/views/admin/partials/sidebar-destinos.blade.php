<aside class="ea-sidebar d-flex flex-column">

  {{-- Logo --}}
  <div class="px-4 py-3 d-flex align-items-center gap-3 border-bottom" style="border-color: var(--ea-line) !important;">
    <div class="ea-logo-badge">
      <i class="bi bi-leaf-fill"></i>
    </div>
    <div class="fw-semibold">Ecoaventura</div>
  </div>

  {{-- Usuario --}}
  <div class="px-4 py-3 border-bottom" style="border-color: var(--ea-line) !important;">
    <div class="fw-semibold">
      {{ auth()->user()->adminDestinos->nombre ?? 'Administrador' }}
      {{ auth()->user()->adminDestinos->apaterno ?? '' }}
      {{ auth()->user()->adminDestinos->amaterno ?? '' }}
    </div>
    <div class="small" style="color: var(--ea-muted);">Administrador de Destinos</div>
  </div>

  {{-- Menú --}}
  <nav class="px-3 py-3 d-grid gap-2">

    <a href="{{ route('misdestinos.index') }}"
       class="ea-nav-pill {{ request()->routeIs('misdestinos.*') ? 'active' : '' }}">
      <i class="bi bi-geo-alt"></i>
      <span>Mis Destinos</span>
    </a>

  </nav>

  {{-- Footer --}}
  <div class="mt-auto px-4 py-3 border-top" style="border-color: var(--ea-line) !important;">
    <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2" style="color: var(--ea-muted);">
      <i class="bi bi-house-door"></i>
      <span>Portal Público</span>
    </a>

    <form class="mt-3" method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-link p-0 text-decoration-none d-flex align-items-center gap-2" style="color:#d14b3a;">
        <i class="bi bi-box-arrow-right"></i>
        <span>Cerrar Sesión</span>
      </button>
    </form>
  </div>

</aside>