<header class="ea-topbar d-flex align-items-center px-4 px-lg-5">
  <div class="d-flex align-items-center gap-3 flex-grow-1">
    <a href="#" class="text-decoration-none" style="color: var(--ea-muted);">
      <i class="bi bi-x-lg"></i>
    </a>

    <div class="small" style="color: var(--ea-muted);">
      <span>Panel</span>
      <span class="mx-2">›</span>
      <span class="fw-semibold" style="color: var(--ea-text);">Administrador de Destinos</span>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    <div class="ea-avatar">
      {{ strtoupper(substr(auth()->user()->adminDestinos->nombre ?? 'A', 0, 1)) }}
    </div>
    <div class="fw-semibold">
      {{ auth()->user()->adminDestinos->nombre ?? 'Administrador' }}
      {{ auth()->user()->adminDestinos->apaterno ?? '' }}
    </div>
  </div>
</header>