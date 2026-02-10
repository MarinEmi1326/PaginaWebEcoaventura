@php $hideNavbar = true; @endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex">

    {{-- SIDEBAR --}}
    @include('hotelero.partials.sidebar')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 min-w-0" style="margin-left: 18rem;">

        {{-- HEADER IGUAL AL DE HABITACIONES (más delgado + icono E + campana circular + contador abajo) --}}
        <header class="bg-white border-b border-gray-200 px-10 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-[#0b6b52] text-white flex items-center justify-center font-extrabold">
                    E
                </div>
                <div class="leading-tight">
                    <div class="text-[15px] font-extrabold text-gray-900">Ecoaventura</div>
                    <div class="text-[13px] text-gray-500">Panel de Servicios</div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right leading-tight">
                    <p class="text-[14px] font-extrabold text-gray-900">
                        Hola, {{ Auth::user()->nombre ?? 'Carlos' }}
                    </p>
                    <p class="text-[13px] text-gray-500">Bienvenido, Hotelero</p>
                </div>

                <div class="relative h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                    <span class="text-lg">🔔</span>
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-[12px] font-bold text-gray-800">
                        3
                    </span>
                </div>

                <div class="h-12 w-12 rounded-full bg-[#0b6b52] text-white flex items-center justify-center font-extrabold">
                    {{ strtoupper(substr(Auth::user()->nombre ?? 'C', 0, 1)) }}M
                </div>
            </div>
        </header>

        {{-- CONTENIDO centrado como Habitaciones --}}
        <div class="px-10 py-8">
            <div class="max-w-6xl mx-auto">

                {{-- TITULO (más pequeño que antes, como Habitaciones) --}}
                <div class="flex items-start gap-4 mb-6">
                    <div class="h-11 w-11 rounded-2xl bg-gray-100 flex items-center justify-center text-xl">
                        🏢
                    </div>
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight"
                            style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">
                            Mi Hotel
                        </h1>
                        <p class="text-base text-gray-500 mt-1">
                            Administra y visualiza la información principal de tu hotel.
                        </p>
                    </div>
                </div>

                {{-- BOTONES (más chicos) --}}
               <div class="flex justify-end gap-3 mb-5">
                    <button onclick="openModal()" class="px-5 py-2.5 rounded-2xl border border-gray-200 bg-white font-bold text-gray-800 hover:bg-gray-50 transition flex items-center gap-2 text-[14px]">
                        📝 Editar
                    </button>

                    {{-- BOTÓN DE SUSPENDER / ACTIVAR --}}
                    <form action="{{ route('hotelero.hotel.suspender') }}" method="POST" onsubmit="return confirm('¿Estás seguro de cambiar el estado de disponibilidad del hotel?')">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2.5 rounded-2xl border transition text-[14px] flex items-center gap-2 font-bold
                                {{ $hotel->estado === 'activo' 
                                    ? 'border-orange-200 bg-orange-50 text-orange-600 hover:bg-orange-100' 
                                    : 'border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            {{ $hotel->estado === 'activo' ? '⏸️ Suspender' : '▶️ Activar' }}
                        </button>
                    </form>
                </div>

                {{-- CARD HOTEL (reducida) --}}
                <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-6 mb-5 flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="h-16 w-16 border border-gray-300 border-dashed rounded-2xl flex flex-col items-center justify-center leading-none">
                            <span class="text-[10px] font-extrabold text-gray-700">SIN</span>
                            <span class="text-[10px] font-extrabold text-gray-700">IMAGEN</span>
                        </div>

                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900">
                                {{ $hotel->nombre ?? 'Maya' }}
                            </h2>

                         <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-extrabold uppercase mt-2
                            {{ $hotel->estado === 'activo' 
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                                : 'bg-red-50 text-red-700 border border-red-200' }}">
                            {{ $hotel->estado === 'activo' ? 'PÚBLICO / ACTIVO' : 'SUSPENDIDO / INACTIVO' }}
                        </span>
                    </div>
                </div>

                    <div class="flex items-center gap-3">
                        <span class="text-[13px] font-bold text-gray-500 uppercase">Estado:</span>
                        <select class="w-44 border border-gray-200 rounded-2xl px-4 py-2.5 text-[14px] font-bold text-gray-800 bg-white outline-none">
                            <option {{ ($hotel->activo ?? true) ? 'selected' : '' }}>Activo</option>
                            <option {{ !($hotel->activo ?? true) ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                {{-- TABS (más delgados) --}}
                <div class="bg-white border border-gray-200 rounded-3xl p-2 mb-5 flex gap-2 shadow-sm">
                    <button class="flex-1 py-2.5 rounded-2xl bg-gray-100 font-extrabold text-gray-900 flex items-center justify-center gap-2 text-[15px]">
                        ℹ️ Información
                    </button>
                    <button class="flex-1 py-2.5 rounded-2xl font-extrabold text-gray-500 hover:bg-gray-50 transition flex items-center justify-center gap-2 text-[15px]">
                        🖼️ Galería
                    </button>
                    <button class="flex-1 py-2.5 rounded-2xl font-extrabold text-gray-500 hover:bg-gray-50 transition flex items-center justify-center gap-2 text-[15px]">
                        ⚙️ Detalles
                    </button>
                </div>

                {{-- CONTENEDOR INFO (reducido) --}}
                <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-8">
                    <h3 class="text-[13px] font-extrabold text-gray-700 uppercase tracking-wider mb-8">
                        Información General
                    </h3>

                    <div class="mb-8">
                        <h4 class="text-[13px] font-extrabold text-emerald-700 uppercase mb-2">Descripción</h4>
                        <p class="text-gray-500 text-[15px]">
                            {{ $hotel->descripcion ?? 'Pendiente de completar' }}
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-7 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex gap-4">
                            <span class="text-red-500 text-xl">📍</span>
                            <div>
                                <div class="text-[12px] font-extrabold text-gray-400 uppercase">Dirección</div>
                                <div class="text-[15px] font-extrabold text-gray-900">
                                    {{ $hotel->direccion ?? 'Calle central sur' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="text-pink-500 text-xl">📞</span>
                            <div>
                                <div class="text-[12px] font-extrabold text-gray-400 uppercase">Teléfono</div>
                                <div class="text-[15px] font-extrabold text-gray-900">
                                    {{ $hotel->telefono ?? '9191784877' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- max-w --}}
        </div>
    </main>
</div>
{{-- MODAL DE EDICIÓN --}}
<div id="modalEditar" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="p-10">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Editar Mi Hotel</h2>
            <form action="{{ route('hotelero.hotel.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-black text-gray-400 uppercase ml-2">Nombre</label>
                    <input type="text" name="nombre" value="{{ $hotel->nombre }}" class="w-full mt-1 border border-gray-200 rounded-2xl px-5 py-3 font-bold text-gray-800 outline-none focus:border-[#0b6b52]">
                </div>
                <div>
                    <label class="text-[11px] font-black text-gray-400 uppercase ml-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full mt-1 border border-gray-200 rounded-2xl px-5 py-3 font-bold text-gray-800 outline-none focus:border-[#0b6b52]">{{ $hotel->descripcion }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-black text-gray-400 uppercase ml-2">Dirección</label>
                        <input type="text" name="direccion" value="{{ $hotel->direccion }}" class="w-full mt-1 border border-gray-200 rounded-2xl px-5 py-3 font-bold text-gray-800 outline-none focus:border-[#0b6b52]">
                    </div>
                    <div>
                        <label class="text-[11px] font-black text-gray-400 uppercase ml-2">Teléfono</label>
                        <input type="text" name="telefono" value="{{ $hotel->telefono }}" class="w-full mt-1 border border-gray-200 rounded-2xl px-5 py-3 font-bold text-gray-800 outline-none focus:border-[#0b6b52]">
                    </div>
                </div>
                <div class="pt-6 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-2xl border border-gray-200 font-extrabold text-gray-500">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 rounded-2xl bg-[#0b6b52] font-extrabold text-white shadow-lg shadow-emerald-900/20">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() { document.getElementById('modalEditar').classList.remove('hidden'); }
    function closeModal() { document.getElementById('modalEditar').classList.add('hidden'); }
</script>

@endsection

