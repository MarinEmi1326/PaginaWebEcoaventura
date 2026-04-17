<header class="ea-topbar d-flex justify-content-between align-items-center px-4 px-lg-5 py-3 border-bottom"
        style="border-color: var(--ea-line) !important;">

    <div class="d-flex align-items-center gap-2 small" style="color: var(--ea-muted);">
        <span>Panel</span>
        <i class="bi bi-chevron-right" style="font-size: .7rem;"></i>
        <span class="fw-semibold" style="color: var(--ea-text);">Gestor de Rutas</span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="ea-avatar" style="width: 34px; height: 34px; font-size: .9rem;">
            {{-- Inicial desde la tabla persona --}}
            {{ strtoupper(substr(auth()->user()->persona->nombre ?? 'G', 0, 1)) }}
        </div>

        <div class="fw-semibold small" style="color: var(--ea-text);">
            {{-- Nombre y apellidos desde la tabla persona --}}
            {{ auth()->user()->persona->nombre ?? 'Gestor' }}
            {{ auth()->user()->persona->apellidos ?? 'de Rutas' }}
        </div>
    </div>
</header>