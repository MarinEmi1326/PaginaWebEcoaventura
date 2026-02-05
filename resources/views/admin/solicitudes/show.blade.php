@extends('layouts.admin')

@section('title', 'Detalle de solicitud')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-4xl font-serif font-semibold text-slate-900">Detalle de solicitud</h1>
            <p class="text-slate-500 mt-1">Información del solicitante y su negocio</p>
        </div>

        <a href="{{ route('admin.solicitudes.index') }}"
           class="px-5 py-3 rounded-xl border-2 border-slate-300 text-slate-800 font-semibold
                  hover:bg-slate-100 hover:border-slate-400 transition">
            ← Volver
        </a>
    </div>

    {{-- Card: Solicitante --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Solicitante</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-slate-500">Nombre</div>
                <div class="font-semibold text-slate-900">
                    {{ $solicitud->nombre }} {{ $solicitud->apaterno }} {{ $solicitud->amaterno }}
                </div>
            </div>

            <div>
                <div class="text-slate-500">Correo</div>
                <div class="font-semibold text-slate-900">{{ $solicitud->correo }}</div>
            </div>

            <div>
                <div class="text-slate-500">Teléfono</div>
                <div class="font-semibold text-slate-900">{{ $solicitud->telefono ?? '—' }}</div>
            </div>

            <div>
                <div class="text-slate-500">Tipo</div>
                <div class="font-semibold text-slate-900">{{ $solicitud->rol }}</div>
            </div>

            <div>
                <div class="text-slate-500">Estado</div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                    {{ $solicitud->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $solicitud->estado === 'aprobado' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $solicitud->estado === 'rechazado' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ $solicitud->estado }}
                </div>
            </div>

            <div>
                <div class="text-slate-500">Fecha solicitud</div>
                <div class="font-semibold text-slate-900">
                    {{ $solicitud->fecha_solicitud ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') : '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Negocio --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Negocio</h2>

        @if($solicitud->rol === 'hotelero')
            @if($hotel)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-slate-500">Nombre del hotel</div>
                        <div class="font-semibold text-slate-900">{{ $hotel->nombre }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Dirección</div>
                        <div class="font-semibold text-slate-900">{{ $hotel->direccion }}</div>
                    </div>
                </div>
            @else
                <div class="text-slate-600">No se encontró un hotel asociado todavía.</div>
            @endif
        @endif

        @if($solicitud->rol === 'restaurantero')
            @if($restaurante)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-slate-500">Nombre del restaurante</div>
                        <div class="font-semibold text-slate-900">{{ $restaurante->nombre }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Dirección</div>
                        <div class="font-semibold text-slate-900">{{ $restaurante->direccion }}</div>
                    </div>
                </div>
            @else
                <div class="text-slate-600">No se encontró un restaurante asociado todavía.</div>
            @endif
        @endif
    </div>

    {{-- Card: Acciones (por ahora solo visual) --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Acciones</h2>

        <div class="flex flex-wrap gap-3">
            <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white font-semibold hover:bg-emerald-800 transition" disabled>
                Aprobar (después)
            </button>

            <button class="px-5 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition" disabled>
                Rechazar (después)
            </button>
        </div>

        <p class="text-xs text-slate-500 mt-3">
            (Luego conectamos estos botones para aprobar/rechazar y mandar correo)
        </p>
    </div>

</div>
@endsection
