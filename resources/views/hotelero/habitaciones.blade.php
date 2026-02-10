@php $hideNavbar = true; @endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f4ee]">
    <div class="flex">

        {{-- SIDEBAR --}}
        @include('hotelero.partials.sidebar')

        {{-- CONTENIDO --}}
        <main class="flex-1 overflow-x-hidden" style="margin-left: 18rem;">


            {{-- HEADER TOP --}}
            <header class="bg-white/60 backdrop-blur border-b border-emerald-900/10 relative z-10">
                <div class="px-10 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-2xl bg-emerald-800 text-white flex items-center justify-center font-semibold">
                            E
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Ecoaventura</div>
                            <div class="text-xs text-slate-500 -mt-0.5">Panel de Servicios</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-slate-800">Hola, {{ Auth::user()->nombre ?? 'Hotelero' }}</div>
                            <div class="text-xs text-slate-500">Bienvenido, {{ ucfirst(Auth::user()->rol ?? 'hotelero') }}</div>
                        </div>

                        <div class="relative">
                            <button class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center">
                                🔔
                            </button>
                            <span class="absolute -top-1 -right-1 h-5 min-w-[20px] px-1 rounded-full bg-amber-400 text-emerald-950 text-xs font-bold flex items-center justify-center">
                                3
                            </span>
                        </div>

                        <div class="h-10 w-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->nombre ?? 'C', 0, 1)) }}{{ strtoupper(substr(Auth::user()->apaterno ?? 'M', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- BODY --}}
            <section class="px-16 py-10 max-w-[1400px] mx-auto">
                <div class="flex items-start justify-between gap-6 flex-wrap">
                    <div>
                        <h1 class="text-4xl font-serif font-semibold text-emerald-950 flex items-center gap-3">
                            <span class="text-emerald-800">📅</span>
                            Reservas de Habitaciones
                        </h1>
                        <p class="text-slate-500 mt-2">Gestiona todas las reservas de tu servicio</p>
                    </div>
                </div>

           <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-col md:flex-row md:items-center gap-4">

                {{-- BUSCAR (ocupa todo lo que pueda) --}}
               <div class="flex-1">
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-xl
                        border border-slate-200 bg-white
                        focus-within:ring-2 focus-within:ring-emerald-200">
                    <!-- ICONO -->
                    <span class="text-slate-400">🔍</span>

                    <!-- INPUT REAL -->
                    <input
                    id="searchInput"
                    type="text"
                    placeholder="Buscar por nombre o email..."
                    class="w-full bg-transparent outline-none text-slate-700 placeholder-slate-400"
                    >
                </div>
                </div>



                {{-- FILTRO (chico y fijo) --}}
                <div class="w-full md:w-64 md:flex-none">
                <select
                    id="estadoSelect"
                    class="w-full py-3 px-4 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-200 bg-white"
                >
                    <option value="">Todos los estados</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="cancelada">Cancelada</option>
                </select>
                </div>

            </div>
            </div>



                {{-- BOTÓN CREAR (debajo del buscador, a la derecha) --}}
                <div class="mt-4 flex justify-end">
                    <a href="#"
                    class="inline-flex items-center gap-2 bg-emerald-800 text-white px-5 py-3 rounded-xl font-semibold hover:bg-emerald-900 transition shadow-sm">
                        ➕ Crear
                    </a>
                </div>
                {{-- TABLA --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Hora</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Personas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider text-right">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="reservasTable" class="divide-y divide-slate-100">
                                @forelse($reservas as $r)
                                    <tr class="hover:bg-slate-50/50 transition-colors"
                                        data-nombre="{{ strtolower($r->cliente_nombre ?? '') }}"
                                        data-correo="{{ strtolower($r->cliente_correo ?? '') }}"
                                        data-estado="{{ strtolower($r->estado ?? '') }}">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-700">{{ $r->cliente_nombre }}</div>
                                            <div class="text-xs text-slate-400">{{ $r->cliente_correo }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $r->fecha }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $r->hora }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $r->personas }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                                {{ $r->estado == 'confirmada' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $r->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{-- Botones de acción --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic">
                                            No hay reservas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </main>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const estadoSelect = document.getElementById('estadoSelect');

    function filtrar() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        const e = (estadoSelect?.value || '').toLowerCase();

        const rows = Array.from(document.querySelectorAll('#reservasTable tr'));

        rows.forEach(tr => {
            if (tr.querySelector('td')?.getAttribute('colspan')) return;

            const nombre = tr.dataset.nombre || '';
            const correo = tr.dataset.correo || '';
            const estado = tr.dataset.estado || '';

            const matchText = !q || nombre.includes(q) || correo.includes(q);
            const matchEstado = !e || estado === e;

            tr.style.display = (matchText && matchEstado) ? '' : 'none';
        });
    }

    searchInput?.addEventListener('input', filtrar);
    estadoSelect?.addEventListener('change', filtrar);
</script>
@endsection
