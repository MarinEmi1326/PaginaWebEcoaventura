<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold">E</div>
        <div>
            <div class="font-semibold text-slate-900">Ecoaventura</div>
            <div class="text-xs text-slate-500 -mt-1">Panel Admin</div>
        </div>
    </div>

    <div class="flex items-center gap-5">
        <div class="relative">
            <span class="text-xl">🔔</span>
            <span class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-600 text-white text-xs flex items-center justify-center">
                3
            </span>
        </div>

        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-slate-200"></div>
            <div class="leading-tight">
                <div class="text-sm font-semibold text-slate-900">Hola, Admin</div>
                <div class="text-xs text-slate-500">Administrador</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-slate-600 hover:text-slate-900">
                Salir
            </button>
        </form>
    </div>
</header>
