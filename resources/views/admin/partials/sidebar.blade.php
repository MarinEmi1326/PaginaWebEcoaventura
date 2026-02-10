<aside class="w-72 bg-emerald-950 text-white flex flex-col">
    <div class="px-6 py-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-emerald-800 flex items-center justify-center font-bold">E</div>
            <div>
                <div class="text-lg font-semibold">Ecoaventura</div>
                <div class="text-xs text-white/70">Panel Admin</div>
            </div>
        </div>
    </div>

    <nav class="px-4 py-5 space-y-1 text-sm">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl
           {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600/30 border border-emerald-400/20' : 'hover:bg-white/5' }}">
            <span>🏠</span> Panel De Control
        </a>

        <a href="{{ route('admin.solicitudes.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
            {{ request()->routeIs('admin.solicitudes.*') ? 'bg-emerald-600/30 border border-emerald-400/20' : 'hover:bg-white/5' }}">
            <span>📥</span> Bandeja de Solicitudes
        </a>

        {{-- Gestión de Destinos (dropdown) --}}
        <details class="group" {{ request()->routeIs('admin.sitios.*') ? 'open' : '' }}>
            <summary class="list-none flex items-center justify-between px-4 py-3 rounded-xl hover:bg-white/5 cursor-pointer">
                <span class="flex items-center gap-3"><span>📍</span> Gestión de Destinos</span>
                <span class="transition-transform group-open:rotate-180 text-white/70">⌄</span>
            </summary>

            <div class="mt-1 space-y-1">
                <a href="{{ route('admin.sitios.index', ['categoria' => 'Turistico']) }}"
                   class="ml-3 flex items-center gap-3 px-4 py-3 rounded-xl
                   {{ request('categoria') === 'Turistico' ? 'bg-emerald-500 text-emerald-950 font-semibold' : 'hover:bg-white/5' }}">
                    <span>⛰️</span> Turístico
                </a>

                <a href="{{ route('admin.sitios.index', ['categoria' => 'Ecoturistico']) }}"
                   class="ml-3 flex items-center gap-3 px-4 py-3 rounded-xl
                   {{ request('categoria') === 'Ecoturistico' ? 'bg-emerald-500 text-emerald-950 font-semibold' : 'hover:bg-white/5' }}">
                    <span>🌿</span> Ecoturístico
                </a>

                <a href="{{ route('admin.sitios.index', ['categoria' => 'Balneario']) }}"
                   class="ml-3 flex items-center gap-3 px-4 py-3 rounded-xl
                   {{ request('categoria') === 'Balneario' ? 'bg-emerald-500 text-emerald-950 font-semibold' : 'hover:bg-white/5' }}">
                    <span>🌊</span> Balneario
                </a>
            </div>
        </details>


        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5">
            <span>📊</span> Reportes
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5">
            <span>👤</span> Perfil del Admin
        </a>
    </nav>


</aside>
