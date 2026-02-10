{{-- resources/views/hotelero/partials/sidebar.blade.php --}}

<aside class="w-72 bg-emerald-900 text-white min-h-screen px-6 py-6 fixed left-0 top-0 overflow-y-auto z-50">
    <div class="flex items-center gap-3 mb-10">
        <div class="h-11 w-11 rounded-2xl bg-white/10 flex items-center justify-center">
            <span class="text-xl">🌿</span>
        </div>
        <div>
            <div class="text-lg font-semibold">Ecoaventura</div>
            <div class="text-xs text-white/70 -mt-0.5">Panel de Servicios</div>
        </div>
    </div>

    <nav class="space-y-2">
        <a href="{{ route('hotelero.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
            <span>🏠</span> <span class="text-sm font-medium">Inicio</span>
        </a>

        <a href="{{ route('hotelero.perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
            <span>👤</span> <span class="text-sm font-medium">Mi Perfil</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
            <span> + </span> <span class="text-sm font-medium">Publicar Hotel</span>
        </a>

        <a href="{{ route('hotelero.habitaciones') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
            <span>🏨</span> <span class="text-sm font-medium">Mi Hotel</span>
        </a>

       <a href="{{ route('hotelero.reservas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
            <span>📅</span> <span class="text-sm font-medium">Reservas</span>
        </a>
        <div class="pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10">
                    <span>🚪</span> <span class="text-sm font-medium">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
