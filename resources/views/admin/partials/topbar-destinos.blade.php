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
      {{-- Obtenemos la inicial desde persona, con 'A' como respaldo --}}
      {{ strtoupper(substr(auth()->user()->persona->nombre ?? 'A', 0, 1)) }}
    </div>
    <div class="fw-semibold">
      {{-- Mostramos nombre y apellidos desde la tabla persona --}}
      {{ auth()->user()->persona->nombre ?? 'Administrador' }}
      {{ auth()->user()->persona->apellidos ?? '' }}
    </div>
  </div>
</header>