@php
$usuario = auth()->user();
$persona = $usuario->persona;
$nombre = $persona->nombre ?? $usuario->correo;
@endphp

<header class="ea-topbar d-flex align-items-center px-4 px-lg-5">
  
  <div class="d-flex align-items-center gap-3 flex-grow-1">
    <a href="#" class="text-decoration-none" style="color: var(--ea-muted);">
      <i class="bi bi-x-lg"></i>
    </a>

    <div class="small" style="color: var(--ea-muted);">
      <span>Panel</span>
      <span class="mx-2">›</span>
      <span class="fw-semibold" style="color: var(--ea-text);">
        Administrador General
      </span>
    </div>
  </div>

  <div class="dropdown">
    <button class="btn btn-user btn-user-sm dropdown-toggle d-flex align-items-center gap-2"
            type="button"
            data-bs-toggle="dropdown">

      {{-- 🔥 AVATAR MODIFICADO --}}
      <div class="ea-avatar d-flex align-items-center justify-content-center overflow-hidden"
           style="width:25px; height:25px; border-radius:40%; background:#DFE6DE;">

        @if($usuario->foto_perfil)
          <img src="{{ asset('storage/' . $usuario->foto_perfil) }}"
               style="width:100%; height:100%; object-fit:cover;">
        @else
          <i class="bi bi-person-fill" style="font-size: 1.4rem; color:#1F2A24;"></i>
        @endif

      </div>

      <span class="fw-semibold">{{ $nombre }}</span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item" href="{{ route('perfil') }}">
          <i class="bi bi-person me-2"></i> Mi perfil
        </a>
      </li>
    </ul>
  </div>

</header>