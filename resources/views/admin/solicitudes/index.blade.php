@extends('layouts.admin')

@section('title', 'Gestión de Solicitudes y Usuarios')

@section('content')
<div class="space-y-6">

  {{-- Encabezado con Botones de Acción --}}
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-4xl font-serif font-semibold text-slate-900">Solicitudes y Usuarios</h1>
      <p class="text-slate-500 mt-1">Historial completo y gestión de estados de cuenta.</p>
    </div>
    <div class="flex gap-3">
      {{-- Botón para Generar Reporte (Corregido para que sea visible) --}}
      <a href="#" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-semibold hover:bg-slate-900 hover:shadow-lg transition-all border border-slate-700">
        <span class="text-base">📊</span>
        Generar Reporte
      </a>
      
      {{-- Botón para Crear Usuario --}}
      <a href="{{ route('admin.solicitudes.create') }}" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm shadow-emerald-100">
        <span class="text-base">+</span>
        Crear Usuario
      </a>
    </div>
  </div>

  {{-- Mensajes de Notificación --}}
  @if (session('ok'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
      {{ session('ok') }}
    </div>
  @endif

  {{-- Tabla de Registros --}}
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="p-5 border-b border-slate-200 bg-slate-50/50">
      <div class="text-xl font-serif font-semibold text-slate-900">
        Registros Totales ({{ $solicitudes->count() }})
      </div>
    </div>

    @if($solicitudes->count() === 0)
      <div class="p-10 text-center text-slate-600">
        <div class="text-4xl mb-2">📂</div>
        <div class="font-semibold">No se encontraron registros</div>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="text-slate-500 bg-slate-50">
            <tr class="border-b border-slate-100">
              <th class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider">Solicitante</th>
              <th class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider">Tipo / Rol</th>
              <th class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider text-center">Fecha Registro</th>
              <th class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider text-center">Estado</th>
              <th class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider text-right">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @foreach($solicitudes as $s)
              @php
                $nombreCompleto = trim(($s->nombre ?? '').' '.($s->apaterno ?? '').' '.($s->amaterno ?? ''));
              @endphp

              <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                {{-- Nombre y Correo --}}
                <td class="px-6 py-4">
                  <div class="font-bold {{ !$s->activo ? 'text-slate-400 italic line-through' : 'text-slate-900' }}">
                    {{ $nombreCompleto ?: 'Sin Nombre' }}
                  </div>
                  <div class="text-slate-500 text-xs">{{ $s->correo }}</div>
                </td>

                {{-- Rol --}}
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-bold capitalize">
                    {{ $s->rol }}
                  </span>
                </td>

                {{-- Fecha (Solo día, mes y año) --}}
                <td class="px-6 py-4 text-center text-slate-600">
                  {{ $s->fecha_solicitud ? \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m/Y') : '—' }}
                </td>

                {{-- Estado con prioridad de Inhabilitación --}}
                <td class="px-6 py-4 text-center">
                  @if(!$s->activo)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold shadow-sm">
                      ⚠️ Inhabilitado
                    </span>
                  @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                      {{ $s->estado === 'aprobado' ? 'bg-emerald-100 text-emerald-800' : '' }}
                      {{ $s->estado === 'rechazado' ? 'bg-red-100 text-red-800' : '' }}
                      {{ $s->estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : '' }}">
                      {{ ucfirst($s->estado) }}
                    </span>
                  @endif
                </td>

                {{-- Botones de Acción (Corregidos con Hover Sólido) --}}
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-3">
                    
                    {{-- Botón Suspender/Habilitar --}}
                    <form action="{{ route('admin.solicitudes.toggle', $s->id_usuario) }}" method="POST">
                      @csrf
                      <button type="submit" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300 border
                        {{ $s->activo 
                            ? 'bg-red-50 text-red-600 border-red-100 hover:bg-red-600 hover:text-white' 
                            : 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-600 hover:text-white' 
                        }} shadow-sm">
                        {{ $s->activo ? ' Suspender' : ' Habilitar' }}
                      </button>
                    </form>

                    {{-- Botón Editar --}}
                    <a href="{{ route('admin.solicitudes.edit', $s->id_usuario) }}" 
                       class="p-2.5 bg-white text-blue-500 rounded-xl border border-slate-200 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 shadow-sm"
                       title="Editar datos">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </a>

                    {{-- Botón Ver (Ojo) --}}
                    <a href="{{ route('admin.solicitudes.show', $s->id_usuario) }}" 
                       class="p-2.5 bg-white text-slate-500 rounded-xl border border-slate-200 hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all duration-300 shadow-sm"
                       title="Ver detalle">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>

                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection