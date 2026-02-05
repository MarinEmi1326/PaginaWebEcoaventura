@extends('layouts.admin')

@section('title', 'Solicitudes')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-4xl font-serif font-semibold text-slate-900">Solicitudes</h1>
        <p class="text-slate-500 mt-1">
            Aquí verás las solicitudes pendientes de hoteleros y restauranteros.
        </p>
    </div>

    {{-- Card tabla --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Header interno --}}
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-3xl font-serif font-semibold text-slate-900">
                Solicitudes ({{ $solicitudes->count() }})
            </h2>
        </div>

        @if($solicitudes->count() === 0)
            <div class="p-10 text-center text-slate-600">
                <div class="text-4xl mb-2">📭</div>
                <div class="font-semibold">No hay solicitudes pendientes</div>
                <div class="text-sm text-slate-500 mt-1">Cuando alguien se registre como hotelero o restaurantero, aparecerá aquí.</div>
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
                                $nombreCompleto = $nombreCompleto !== '' ? $nombreCompleto : '—';

                                $fechaTxt = $s->fecha_solicitud
                                    ? \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m/Y')
                                    : '—';
                            @endphp

                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                {{-- Solicitante --}}
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $nombreCompleto }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $s->correo }}</div>
                                </td>

                                {{-- Tipo --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 bg-slate-100 text-slate-700 text-xs font-semibold">
                                        {{ $s->rol }}
                                    </span>
                                </td>

                                {{-- Fecha --}}
                                <td class="px-6 py-4 text-slate-700">
                                    {{ $fechaTxt }}
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4">
                                    @php
                                        $estado = strtolower($s->estado ?? 'pendiente');
                                    @endphp

                                    @if($estado === 'aprobado')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full
                                                     bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                            Aprobada
                                        </span>
                                    @elseif($estado === 'rechazado')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full
                                                     bg-red-100 text-red-700 text-xs font-semibold">
                                            Rechazada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full
                                                     bg-amber-100 text-amber-800 text-xs font-semibold">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.solicitudes.show', $s->id_usuario) }}"
                                           class="text-slate-500 hover:text-slate-900"
                                           title="Ver detalles">
                                            👁️
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
