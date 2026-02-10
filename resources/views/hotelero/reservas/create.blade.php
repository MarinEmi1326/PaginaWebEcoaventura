@php $hideNavbar = true; @endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f4ee] flex">

    {{-- SIDEBAR --}}
    @include('hotelero.partials.sidebar')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 p-10" style="margin-left: 18rem;">
        <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            
            {{-- CABECERA --}}
            <div class="bg-emerald-900 p-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">
                        📅
                    </div>
                    <div>
                        <h2 class="text-2xl font-serif font-semibold">Nueva Reserva</h2>
                        <p class="text-emerald-200/70 text-sm">Registro manual de hospedaje</p>
                    </div>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <form action="{{ route('hotelero.reservas.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                {{-- Selección de Turista --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-emerald-900 uppercase tracking-wider">
                        Cliente / Turista
                    </label>
                    <div class="relative">
                        <select name="id_turista" required 
                                class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition appearance-none">
                            <option value="" disabled selected>Seleccione un cliente...</option>
                            @foreach($turistas as $t)
                                <option value="{{ $t->id_turista }}">
                                    {{ $t->nombre }} {{ $t->apaterno }} (ID: #{{ $t->id_turista }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            ▼
                        </div>
                    </div>
                    @error('id_turista') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Selección de Habitación --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-emerald-900 uppercase tracking-wider">
                        Habitación Disponible
                    </label>
                    <div class="relative">
                        <select name="id_habitacion" required 
                                class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition appearance-none">
                            <option value="" disabled selected>Seleccione habitación...</option>
                            @foreach($habitaciones as $h)
                                <option value="{{ $h->id_habitacion }}">
                                    {{ $h->tipo }} - Hab. #{{ $h->id_habitacion }} (${{ number_format($h->precio, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            ▼
                        </div>
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-emerald-900 uppercase tracking-wider">
                            Fecha de Entrada
                        </label>
                        <input type="date" name="fecha_entrada" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-emerald-900 uppercase tracking-wider">
                            Fecha de Salida
                        </label>
                        <input type="date" name="fecha_salida" required
                               class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    </div>
                </div>

                {{-- Nota informativa --}}
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-3">
                    <span class="text-amber-600">💡</span>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Al confirmar, la reserva se registrará con estado <strong>"Pendiente"</strong>. Podrás confirmarla o cancelarla desde el control de reservas.
                    </p>
                </div>

                {{-- Botones --}}
                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 bg-emerald-800 text-white py-4 rounded-2xl font-bold hover:bg-emerald-900 transition shadow-lg shadow-emerald-900/20">
                        Confirmar y Registrar
                    </button>
                    <a href="{{ route('hotelero.reservas') }}" 
                       class="flex-1 bg-slate-100 text-slate-600 py-4 rounded-2xl font-bold text-center hover:bg-slate-200 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection