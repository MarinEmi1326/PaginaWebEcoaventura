@extends('layouts.admin')

@section('title', 'Solicitudes')

@section('content')
<div class="space-y-6">

  <div>
    <h1 class="text-4xl font-serif font-semibold text-slate-900">Solicitudes</h1>
    <p class="text-slate-500 mt-1">Aquí verás las solicitudes pendientes de hoteleros y restauranteros.</p>
  </div>

  @if (session('ok'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
      {{ session('ok') }}
    </div>
  @endif

  @if (session('error'))
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
      {{ session('error') }}
    </div>
  @endif

  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
    <div class="p-5 border-b border-slate-200">
      <div class="text-2xl font-serif font-semibold text-slate-900">
        Solicitudes ({{ $solicitudes->count() }})
      </div>
    </div>

    @if($solicitudes->count() === 0)
      <div class="p-10 text-center text-slate-600">
        <div class="text-4xl mb-2">✅</div>
        <div class="font-semibold">No hay solicitudes pendientes</div>
        <div class="text-sm text-slate-500 mt-1">
          Cuando un hotelero o restaurantero se registre, aparecerá aquí.
        </div>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-slate-500">
            <tr class="text-left border-b border-slate-100">
              <th class="px-6 py-4 font-semibold">Solicitante</th>
              <th class="px-6 py-4 font-semibold">Tipo</th>
              <th class="px-6 py-4 font-semibold">Fecha</th>
              <th class="px-6 py-4 font-semibold">Estado</th>
              <th class="px-6 py-4 font-semibold text-right">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @foreach($solicitudes as $s)
              @php
                $nombreCompleto = trim(($s->nombre ?? '').' '.($s->apaterno ?? '').' '.($s->amaterno ?? ''));
                $showUrl = route('admin.solicitudes.show', $s->id_usuario);
              @endphp

              <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                  <div class="font-semibold text-slate-900">{{ $nombreCompleto ?: '—' }}</div>
                  <div class="text-slate-500 text-xs">{{ $s->correo }}</div>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-semibold">
                    {{ $s->rol }}
                  </span>
                </td>

                <td class="px-6 py-4 text-slate-700">
                  {{ $s->fecha_solicitud ? \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m/Y H:i') : '—' }}
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold">
                    Pendiente
                  </span>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-4">
                    <a href="{{ $showUrl }}" class="text-slate-500 hover:text-slate-800" title="Ver detalle">👁️</a>
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
