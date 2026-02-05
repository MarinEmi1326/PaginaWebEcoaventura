@extends('layouts.hotelero')

@section('title', 'Dashboard Hotelero')

@section('content')

@php
    // Conteos por estado (ajusta los textos si en tu BD son diferentes)
    $totalReservas = $reservas->count();
    $pendientes    = $reservas->where('estado', 'pendiente')->count();
    $confirmadas   = $reservas->where('estado', 'confirmada')->count();
    $canceladas    = $reservas->where('estado', 'cancelada')->count();

    // Últimas 5 reservas
    $ultimasReservas = $reservas->take(5);
@endphp

<h1 class="text-3xl font-extrabold text-[#1f2a2a] mb-1">
    Buenas noches, {{ auth()->user()->nombre ?? 'Hotelero' }} 👋
</h1>
<p class="text-gray-500 mb-8">Aquí tienes un resumen de tu actividad</p>

{{-- CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500 font-medium">Total Reservas</div>
                <div class="text-3xl font-extrabold mt-2">{{ $totalReservas }}</div>
                <div class="text-xs text-emerald-600 mt-2">↑ 12% vs mes anterior</div>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-50 flex items-center justify-center text-xl">📅</div>
        </div>
    </div>

    <div class="bg-[#FFF7E8] rounded-2xl border border-black/5 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500 font-medium">Pendientes</div>
                <div class="text-3xl font-extrabold mt-2">{{ $pendientes }}</div>
            </div>
            <div class="h-12 w-12 rounded-xl bg-[#FFE7BD] flex items-center justify-center text-xl">🕒</div>
        </div>
    </div>

    <div class="bg-[#ECFFF2] rounded-2xl border border-black/5 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500 font-medium">Confirmadas</div>
                <div class="text-3xl font-extrabold mt-2">{{ $confirmadas }}</div>
            </div>
            <div class="h-12 w-12 rounded-xl bg-[#CFFBE0] flex items-center justify-center text-xl">✅</div>
        </div>
    </div>

    <div class="bg-[#FFEDEE] rounded-2xl border border-black/5 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500 font-medium">Canceladas</div>
                <div class="text-3xl font-extrabold mt-2">{{ $canceladas }}</div>
            </div>
            <div class="h-12 w-12 rounded-xl bg-[#FFD2D6] flex items-center justify-center text-xl">✖</div>
        </div>
    </div>

</div>

{{-- 2 COLUMNAS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- MI SERVICIO --}}
    <div class="bg-white rounded-2xl border border-black/5 shadow-sm">
        <div class="p-6 border-b border-black/5 flex items-center justify-between">
            <div>
                <div class="text-lg font-bold">Mi Servicio</div>
                <div class="text-sm text-gray-500">Información de tu hotel</div>
            </div>

            @if(!empty($hotel))
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                    Activo
                </span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    Sin hotel
                </span>
            @endif
        </div>

        <div class="p-6">
            @if(!empty($hotel))
                <div class="flex gap-5">
                    <div class="h-28 w-28 rounded-2xl bg-gray-100 border border-black/5 flex items-center justify-center">
                        <span class="text-gray-400">IMG</span>
                    </div>

                    <div class="flex-1">
                        <div class="text-xl font-extrabold mb-1">
                            {{ $hotel->nombre ?? 'Tu Hotel' }}
                        </div>

                        <div class="text-sm text-gray-500 space-y-1">
                            <div>📍 {{ $hotel->direccion ?? 'Dirección no registrada' }}</div>
                            <div>📞 {{ $hotel->telefono ?? 'Teléfono no registrado' }}</div>
                            <div>🕘 {{ $hotel->horario ?? 'Recepción 24 horas' }}</div>
                        </div>

                        <div class="flex gap-3 mt-5">
                            <a href="#"
                               class="px-4 py-2 rounded-xl border border-black/10 hover:bg-gray-50 font-semibold text-sm">
                                👁 Ver Detalles
                            </a>
                            <a href="#"
                               class="px-4 py-2 rounded-xl border border-black/10 hover:bg-gray-50 font-semibold text-sm">
                                ✏️ Editar
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-sm text-gray-600">
                    Aún no tienes un hotel publicado. Ve a <b>“Publicar Hotel”</b> para crear tu servicio.
                </div>
            @endif
        </div>
    </div>

    {{-- ÚLTIMAS RESERVAS --}}
    <div class="bg-white rounded-2xl border border-black/5 shadow-sm">
        <div class="p-6 border-b border-black/5">
            <div class="text-lg font-bold">Últimas Reservas</div>
            <div class="text-sm text-gray-500">Las 5 reservas más recientes</div>
        </div>

        <div class="p-3">
            @forelse($ultimasReservas as $r)
                @php
                    $estado = strtolower($r->estado ?? 'pendiente');

                    $badge = match($estado) {
                        'confirmada' => 'bg-emerald-50 text-emerald-700',
                        'pendiente'  => 'bg-amber-50 text-amber-700',
                        'atendida'   => 'bg-slate-100 text-slate-700',
                        'cancelada'  => 'bg-rose-50 text-rose-700',
                        default      => 'bg-gray-100 text-gray-700',
                    };

                    // Ajusta a tus columnas reales de reserva_hotel
                    $cliente  = $r->cliente_nombre ?? $r->nombre_cliente ?? 'Cliente';
                    $fecha    = $r->fecha ?? optional($r->created_at)->format('d/m/Y');
                    $hora     = $r->hora ?? '—';
                    $personas = $r->personas ?? $r->num_personas ?? '—';
                @endphp

                <div class="flex items-center justify-between gap-3 px-4 py-4 rounded-2xl hover:bg-gray-50 transition">
                    <div>
                        <div class="font-bold text-[#1f2a2a]">{{ $cliente }}</div>
                        <div class="text-sm text-gray-500 flex flex-wrap gap-3 mt-1">
                            <span>📅 {{ $fecha }}</span>
                            <span>🕒 {{ $hora }}</span>
                            <span>👥 {{ $personas }} personas</span>
                        </div>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ ucfirst($estado) }}
                    </span>
                </div>
            @empty
                <div class="px-6 py-10 text-sm text-gray-600">
                    Aún no hay reservas registradas.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection