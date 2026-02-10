@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    <div class="mb-6">
        <a href="{{ route('admin.solicitudes.index') }}" class="text-emerald-600 font-medium flex items-center gap-2 mb-4">
            ← Volver al listado
        </a>
        <h1 class="text-3xl font-serif font-bold text-slate-900">Editar Usuario</h1>
        <p class="text-slate-500">Modifica la información de la cuenta de <b>{{ $usuario->correo }}</b></p>
    </div>

    <form action="{{ route('admin.solicitudes.update', $usuario->id_usuario) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 font-semibold text-slate-800">
                🔑 Credenciales y Acceso
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="correo" value="{{ $usuario->correo }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para no cambiar" class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Rol</label>
                    <select name="rol" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="hotelero" {{ $usuario->rol == 'hotelero' ? 'selected' : '' }}>Hotelero</option>
                        <option value="restaurantero" {{ $usuario->rol == 'restaurantero' ? 'selected' : '' }}>Restaurantero</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 font-semibold text-slate-800">
                👤 Información Personal
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre(s)</label>
                    <input type="text" name="nombre" value="{{ $usuario->nombre ?? '' }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Apellido Paterno</label>
                    <input type="text" name="apaterno" value="{{ $usuario->apaterno ?? '' }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Apellido Materno</label>
                    <input type="text" name="amaterno" value="{{ $usuario->amaterno ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $usuario->telefono ?? '' }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-10 border-t pt-8">
            <button type="submit" class="px-10 py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                Actualizar Datos del Usuario
            </button>
        </div>
    </form>
</div>
@endsection