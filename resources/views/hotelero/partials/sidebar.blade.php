{{-- resources/views/hotelero/partials/sidebar.blade.php --}}

<aside class="w-72 bg-emerald-900 text-white min-h-screen px-6 py-6 fixed left-0 top-0 overflow-y-auto z-50 shadow-2xl">
    {{-- LOGO --}}
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="h-11 w-11 rounded-2xl bg-white/10 flex items-center justify-center border border-white/10">
            <span class="text-xl">🌿</span>
        </div>
        <div>
            <div class="text-lg font-bold tracking-tight">Ecoaventura</div>
            <div class="text-[10px] text-emerald-300 font-bold uppercase tracking-widest -mt-1">Panel de Control</div>
        </div>
    </div>

    {{-- NAVEGACIÓN --}}
    <nav class="space-y-1">
        {{-- INICIO --}}
        <a href="{{ route('hotelero.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hotelero.index') ? 'bg-white/20 text-white font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span class="text-lg">🏠</span> <span class="text-sm">Inicio</span>
        </a>

        {{-- MI PERFIL --}}
        <a href="{{ route('hotelero.perfil') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hotelero.perfil') ? 'bg-white/20 text-white font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span class="text-lg">👤</span> <span class="text-sm">Mi Perfil</span>
        </a>

        <div class="my-4 border-t border-white/5 mx-4"></div>

        {{-- MI HOTEL (Vista General) --}}
        <a href="{{ route('hotelero.mi-hotel') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hotelero.mi-hotel') ? 'bg-white/20 text-white font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span class="text-lg">🏨</span> <span class="text-sm">Mi Hotel</span>
        </a>

        {{-- HABITACIONES --}}
        <a href="{{ route('hotelero.habitaciones') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hotelero.habitaciones') ? 'bg-white/20 text-white font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span class="text-lg">🔑</span> <span class="text-sm">Habitaciones</span>
        </a>

        {{-- RESERVAS --}}
        <a href="{{ route('hotelero.reservas') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hotelero.reservas*') ? 'bg-white/20 text-white font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span class="text-lg">📅</span> <span class="text-sm">Reservas</span>
        </a>

        {{-- PUBLICAR HOTEL (Deshabilitado o con ruta si la tienes) --}}
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/40 cursor-not-allowed italic">
            <span class="text-lg">✨</span> <span class="text-sm">Publicar Hotel</span>
        </a>

        {{-- CERRAR SESIÓN --}}
        <div class="pt-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-300 hover:bg-rose-500/10 transition-colors">
                    <span class="text-lg">🚪</span> <span class="text-sm font-semibold">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </nav>
</aside>