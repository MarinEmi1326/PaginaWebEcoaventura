@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-semibold text-gray-900">Mi Perfil</h1>
    <p class="text-gray-600 mt-2">Gestiona tu información personal</p>
    
    <!-- Información de perfil -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apaterno, 0, 1)) }}</span>
            </div>
            <div>
                <h2 class="text-xl font-semibold">{{ Auth::user()->nombre }} {{ Auth::user()->apaterno }}</h2>
                <p class="text-gray-500">{{ Auth::user()->correo }}</p>
                <p class="text-gray-500">Rol: {{ ucfirst(Auth::user()->rol) }}</p>
                <p class="text-gray-500">Miembro desde {{ Auth::user()->created_at->format('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Sección de información personal -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900">Información Personal</h2>
        <form action="{{ route('hotelero.updateProfile') }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="nombre_completo" class="block text-sm font-medium text-gray-600">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" value="{{ Auth::user()->nombre }} {{ Auth::user()->apaterno }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled />
            </div>

            <div class="mb-4">
                <label for="correo" class="block text-sm font-medium text-gray-600">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ Auth::user()->correo }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled />
            </div>

            <div class="mb-4">
                <label for="telefono" class="block text-sm font-medium text-gray-600">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="{{ $hotelero->telefono }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Guardar Cambios</button>
            </div>
        </form>
    </div>

</div>
@endsection
