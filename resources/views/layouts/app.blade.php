<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ecoaventura</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }

    /* Barra glass suave y fija */
    .glass-bar{
      background: rgba(255,255,255,0.48);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid rgba(255,255,255,0.55);
      box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
  </style>
</head>

<body class="bg-white text-slate-900">

{{-- NAVBAR (se oculta si $hideNavbar = true) --}}
@if (!isset($hideNavbar) || !$hideNavbar)

<!-- NAVBAR FIJO -->
<header class="fixed top-0 left-0 w-full z-[9999] glass-bar">
  <div class="max-w-screen-xl mx-auto px-6">
    <nav class="h-[74px] flex items-center justify-between">

      <!-- LOGO -->
      <a href="{{ url('/') }}" class="flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-emerald-800">
          <span class="text-white text-lg">🌿</span>
        </span>
        <span class="text-lg font-semibold text-emerald-950">
          Ecoaventura
        </span>
      </a>

      <!-- MENU -->
      <ul class="hidden md:flex items-center gap-2 text-sm font-medium text-emerald-950/80">

        <li>
          <a href="{{ url('/') }}"
             class="rounded-full px-5 py-2 hover:bg-emerald-100/70 hover:text-emerald-900 transition">
            Inicio
          </a>
        </li>

       <li class="relative group">
          <a href="#"
            class="rounded-full px-5 py-2 hover:bg-emerald-100/70 hover:text-emerald-900 transition inline-flex items-center gap-1">
            Destinos <span class="text-xs">▾</span>
          </a>

          <!-- Contenedor puente (evita que se cierre) -->
          <div class="absolute left-0 top-full pt-2 hidden group-hover:block">
            <div class="w-56 rounded-2xl bg-white/80 backdrop-blur-md
                        ring-1 ring-black/10 shadow-lg p-2">

              <a href="#"
                class="block rounded-xl px-3 py-2 text-sm hover:bg-emerald-50">
                Turísticos
              </a>

              <a href="#"
                class="block rounded-xl px-3 py-2 text-sm hover:bg-emerald-50">
                Ecoturísticos
              </a>

              <a href="#"
                class="block rounded-xl px-3 py-2 text-sm hover:bg-emerald-50">
                Balnearios
              </a>
            </div>
          </div>
        </li>


        <li class="relative group">
          <a href="#"
            class="rounded-full px-5 py-2 hover:bg-emerald-100/70 hover:text-emerald-900 transition inline-flex items-center gap-1">
            Servicios <span class="text-xs">▾</span>
          </a>

          <div class="absolute left-0 top-full pt-2 hidden group-hover:block">
            <div class="w-60 rounded-2xl bg-white/80 backdrop-blur-md
                        ring-1 ring-black/10 shadow-lg p-2">
              <a href="#" class="block rounded-xl px-3 py-2 text-sm hover:bg-emerald-50">Hospedaje</a>
              <a href="#" class="block rounded-xl px-3 py-2 text-sm hover:bg-emerald-50">Restaurantes</a>
            </div>
          </div>
        </li>


        <li><a href="#" class="rounded-full px-5 py-2 hover:bg-emerald-100/70">Nosotros</a></li>
        <li><a href="#" class="rounded-full px-5 py-2 hover:bg-emerald-100/70">Contacto</a></li>
      </ul>

      <!-- ACCIONES -->
      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}"
           class="hidden sm:inline-flex rounded-full bg-white/50 px-5 py-2 text-sm font-semibold ring-1 ring-black/5">
          Iniciar sesión
        </a>

        <a href="{{ route('register') }}"
           class="rounded-full bg-emerald-800 px-5 py-2 text-sm font-semibold text-white">
          Registrarse
        </a>
      </div>

    </nav>
  </div>
</header>

<!-- ESPACIO NAVBAR -->
<div class="h-[74px]"></div>

@endif

<main>
  @yield('content')
</main>

</body>
</html>
