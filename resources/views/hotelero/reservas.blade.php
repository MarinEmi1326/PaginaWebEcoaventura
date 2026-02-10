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
                            <div class="text-xs text-slate-500 -mt-0.5">Gestión de Reservas</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-slate-800">Hola, {{ Auth::user()->nombre ?? 'Hotelero' }}</div>
                            <div class="text-xs text-slate-500">{{ ucfirst(Auth::user()->rol) }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->nombre ?? 'H', 0, 1)) }}
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
                            Control de Reservas
                        </h1>
                        <p class="text-slate-500 mt-2">Historial y solicitudes de hospedaje</p>
                    </div>
                    <a href="{{ route('hotelero.reservas.create') }}"
                        class="inline-flex items-center gap-2 bg-emerald-800 text-white px-5 py-3 rounded-xl font-semibold hover:bg-emerald-900 transition shadow-sm">
                        ➕ Nueva Reserva
                    </a>
                </div>

                {{-- FILTROS --}}
                <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 bg-white focus-within:ring-2 focus-within:ring-emerald-200">
                                <span class="text-slate-400">🔍</span>
                                <input id="searchInput" type="text" placeholder="Buscar por cliente..." class="w-full bg-transparent outline-none text-slate-700">
                            </div>
                        </div>
                        <div class="w-full md:w-64">
                            <select id="estadoSelect" class="w-full py-3 px-4 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="">Todos los estados</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- TABLA --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Entrada / Salida</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Habitación</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-xs font-bold text-emerald-900 uppercase tracking-wider text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="reservasTable" class="divide-y divide-slate-100">
                                @forelse($reservas as $r)
                                    <tr class="hover:bg-slate-50/50 transition-colors" 
                                        data-nombre="{{ strtolower($r->turista->nombre ?? '') }}"
                                        data-estado="{{ strtolower($r->estado) }}">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-700">
                                                {{ $r->turista->nombre ?? 'N/A' }} {{ $r->turista->apaterno ?? '' }}
                                            </div>
                                            <div class="text-xs text-slate-400">ID: #{{ $r->id_reserva }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-600">IN: {{ $r->fecha_entrada }}</div>
                                            <div class="text-sm text-slate-600">OUT: {{ $r->fecha_salida }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            {{ $r->habitacion->tipo ?? 'Hab.' }} (#{{ $r->id_habitacion }})
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                                @if($r->estado == 'confirmada') bg-emerald-100 text-emerald-700 
                                                @elseif($r->estado == 'cancelada') bg-rose-100 text-rose-700 
                                                @else bg-amber-100 text-amber-700 @endif">
                                                {{ $r->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('hotelero.reservas.show', $r->id_reserva) }}" class="inline-block text-slate-400 hover:text-emerald-800 transition" title="Ver detalles">
                                                👁️
                                            </a>
                                            
                                            @if($r->estado == 'pendiente')
                                                <form action="{{ route('hotelero.reservas.aprobar', $r->id_reserva) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-emerald-600 hover:scale-110 transition" title="Confirmar">✅</button>
                                                </form>
                                                <form action="{{ route('hotelero.reservas.rechazar', $r->id_reserva) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 hover:scale-110 transition" title="Cancelar">❌</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">No hay reservas registradas.</td>
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
        const q = searchInput.value.toLowerCase().trim();
        const e = estadoSelect.value.toLowerCase();
        const rows = document.querySelectorAll('#reservasTable tr');

        rows.forEach(tr => {
            const nombre = tr.dataset.nombre || '';
            const estado = tr.dataset.estado || '';
            const matchText = !q || nombre.includes(q);
            const matchEstado = !e || estado === e;
            tr.style.display = (matchText && matchEstado) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filtrar);
    estadoSelect.addEventListener('change', filtrar);
</script>
@endsection