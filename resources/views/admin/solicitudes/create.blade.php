@extends('layouts.admin')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    {{-- Encabezado --}}
    <div class="mb-6">
        <a href="{{ route('admin.solicitudes.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium transition-colors mb-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Volver al listado
        </a>
        <h1 class="text-3xl font-serif font-bold text-slate-900">Registrar Nuevo Usuario</h1>
        <p class="text-slate-500">El usuario creado tendrá acceso inmediato al sistema.</p>
    </div>

    <form action="{{ route('admin.solicitudes.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Bloque 1: Credenciales --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg text-sm">🔑</span>
                    Datos de Acceso al Sistema
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Correo Electrónico</label>
                    <input type="email" name="correo" value="{{ old('correo') }}" required 
                        placeholder="ejemplo@correo.com"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                    @error('correo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Contraseña</label>
                    <input type="password" name="password" required 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Rol del Usuario</label>
                    <select name="rol" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white transition-all">
                        <option value="hotelero">🏨 Hotelero</option>
                        <option value="restaurantero">🍴 Restaurantero</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Bloque 2: Información Personal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg text-sm">👤</span>
                    Información Personal del Solicitante
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Nombre(s)</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                            placeholder="Ingrese nombres"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Apellido Paterno</label>
                        <input type="text" name="apaterno" value="{{ old('apaterno') }}" required 
                            placeholder="Apellido 1"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Apellido Materno</label>
                        <input type="text" name="amaterno" value="{{ old('amaterno') }}" 
                            placeholder="Apellido 2 "
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-bold text-slate-700 text-emerald-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Número de Teléfono / WhatsApp
                        </label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" required 
                            placeholder="Ej: 9671234567"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all font-mono">
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="mt-10 flex flex-col md:flex-row items-center justify-end gap-4 border-t border-slate-100 pt-8">
            
            {{-- Botón Limpiar: Ahora con borde y fondo sutil para que se note --}}
            <button type="reset" class="w-full md:w-auto px-6 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 hover:text-slate-800 transition-all flex items-center justify-center gap-2 border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Limpiar Formulario
            </button>

            {{-- Botón Guardar: El protagonista --}}
            <button type="submit" class="w-full md:w-auto px-10 py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 border-b-4 border-emerald-800 active:border-b-0 active:translate-y-1">
                <span>Guardar y Habilitar Usuario</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
            
        </div>
    </form>
</div>
@endsection