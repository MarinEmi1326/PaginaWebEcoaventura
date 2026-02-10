@php $hideNavbar = true; @endphp

@extends('layouts.hotelero')

@section('title', 'Editar Información del Hotel')

@section('content')

<div class="max-w-4xl">
    <h1 class="text-3xl font-extrabold text-[#1f2a2a] mb-1">
        Editar mi Hotel ✏️
    </h1>
    <p class="text-gray-500 mb-8">Actualiza los datos públicos de tu establecimiento</p>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-8">
        <form action="{{ route('hotelero.hotel.update') }}" method="POST">
            @csrf
            {{-- Usamos POST porque así lo definimos en el controlador anteriormente --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre del Hotel --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold text-gray-700">Nombre del Establecimiento</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $hotel->nombre) }}" 
                           class="px-4 py-3 rounded-xl border border-black/10 focus:ring-2 focus:ring-emerald-500 outline-none transition" required>
                </div>

                {{-- Teléfono --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold text-gray-700">Teléfono de Contacto</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $hotel->telefono) }}" 
                           class="px-4 py-3 rounded-xl border border-black/10 focus:ring-2 focus:ring-emerald-500 outline-none transition" required>
                </div>

                {{-- Dirección --}}
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label class="text-sm font-bold text-gray-700">Dirección Física</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $hotel->direccion) }}" 
                           class="px-4 py-3 rounded-xl border border-black/10 focus:ring-2 focus:ring-emerald-500 outline-none transition" required>
                </div>

                {{-- Descripción --}}
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label class="text-sm font-bold text-gray-700">Descripción breve</label>
                    <textarea name="descripcion" rows="4" 
                              class="px-4 py-3 rounded-xl border border-black/10 focus:ring-2 focus:ring-emerald-500 outline-none transition">{{ old('descripcion', $hotel->descripcion) }}</textarea>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="px-8 py-3 bg-emerald-900 text-white font-bold rounded-xl hover:bg-emerald-800 transition shadow-lg">
                    Guardar Cambios
                </button>
                <a href="{{ route('hotelero.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection