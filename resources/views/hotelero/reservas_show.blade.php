@php $hideNavbar = true; @endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f4ee] flex">

    {{-- SIDEBAR --}}
    @include('hotelero.partials.sidebar')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 p-10" style="margin-left: 18rem;">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            
            {{-- CABECERA DEL DETALLE --}}
            <div class="bg-emerald-900 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-serif">Detalle de la Reserva #{{ $reserva->id_reserva }}</h2>
                    <p class="text-emerald-200 text-sm">Registrada el {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <a href="{{ route('hotelero.reservas') }}" class="text-sm bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <span>←</span> Volver al listado
                </a>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    {{-- SECCIÓN: DATOS DEL CLIENTE --}}
                    <div>
                        <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="bg-emerald-100 p-1 rounded">👤</span> Información del Cliente
                        </h3>
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <p class="text-sm text-slate-500 uppercase font-semibold">Nombre Completo</p>
                            <p class="text-lg font-semibold text-slate-900 mb-4">
                                {{ $reserva->turista->nombre ?? 'No asignado' }} {{ $reserva->turista->apaterno ?? '' }}
                            </p>

                            <p class="text-sm text-slate-500 uppercase font-semibold">ID de Cliente</p>
                            <p class="text-slate-700">#{{ $reserva->id_turista }}</p>
                        </div>
                    </div>

                    {{-- SECCIÓN: DETALLES DE LA ESTANCIA --}}
                    <div>
                        <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="bg-emerald-100 p-1 rounded">🏨</span> Detalles de Estancia
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Check-in:</span>
                                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Check-out:</span>
                                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Habitación:</span>
                                <span class="font-semibold text-emerald-700">{{ $reserva->habitacion->tipo ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-slate-500">Estado actual:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                    @if($reserva->estado == 'confirmada') bg-emerald-100 text-emerald-800 
                                    @elseif($reserva->estado == 'cancelada') bg-rose-100 text-rose-800 
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ $reserva->estado }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-8 border-slate-100">

                {{-- ACCIONES RÁPIDAS --}}
                <div class="flex justify-end gap-4">
                    @if($reserva->estado == 'pendiente')
                        <form action="{{ route('hotelero.reservas.rechazar', $reserva->id_reserva) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 border border-rose-200 text-rose-600 rounded-xl hover:bg-rose-50 transition font-semibold">
                                Cancelar Reserva
                            </button>
                        </form>
                        <form action="{{ route('hotelero.reservas.aprobar', $reserva->id_reserva) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-emerald-800 text-white rounded-xl hover:bg-emerald-900 transition font-semibold shadow-md">
                                Confirmar Reserva
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection