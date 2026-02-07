@extends('layouts.admin')

@section('title', 'Detalle de solicitud')

@section('content')
<div class="space-y-6">

    <div>
        <a href="{{ route('admin.solicitudes.index') }} "
           class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
            ← Regresar a solicitudes
        </a>

        <h1 class="mt-3 text-4xl font-serif font-semibold text-slate-900">Detalle de Solicitud</h1>
        <p class="text-slate-500 mt-1">Revisa la información y aprueba o rechaza la solicitud.</p>
    </div>

    {{-- Mensajes --}}
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

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="font-semibold mb-2">Revisa los campos:</div>
            <ul class="list-disc ml-5 space-y-1 text-sm">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Solicitante</div>
                <div class="text-2xl font-serif font-semibold text-slate-900">
                    {{ trim($solicitud->nombre.' '.$solicitud->apaterno.' '.$solicitud->amaterno) }}
                </div>
                <div class="text-slate-500 text-sm">{{ $solicitud->correo }}</div>
            </div>

            <div class="text-right">
                <div class="text-sm text-slate-500">Tipo</div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-semibold">
                    {{ $solicitud->rol }}
                </span>

                <div class="mt-3 text-sm text-slate-500">Estado</div>
                @php
                    $estado = $solicitud->estado ?? 'pendiente';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full
                    {{ $estado === 'aprobado' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $estado === 'rechazado' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : '' }}
                    text-xs font-semibold">
                    {{ ucfirst($estado) }}
                </span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-slate-200 p-5">
                <div class="text-sm font-semibold text-slate-900 mb-3">Datos personales</div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Teléfono</span>
                        <span class="text-slate-900 font-semibold">{{ $solicitud->telefono ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Fecha solicitud</span>
                        <span class="text-slate-900 font-semibold">
                            {{ $solicitud->fecha_solicitud ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') : '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Fecha respuesta</span>
                        <span class="text-slate-900 font-semibold">
                            {{ $solicitud->fecha_respuesta ? \Carbon\Carbon::parse($solicitud->fecha_respuesta)->format('d/m/Y H:i') : '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Activo</span>
                        <span class="text-slate-900 font-semibold">
                            {{ $solicitud->activo ?? '—' }}
                        </span>
                    </div>
                </div>

                @if(!empty($solicitud->motivo_rechazo))
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="text-sm font-semibold text-red-800">Motivo de rechazo</div>
                        <div class="text-sm text-red-700 mt-1">{{ $solicitud->motivo_rechazo }}</div>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 p-5">
                <div class="text-sm font-semibold text-slate-900 mb-3">
                    {{ $solicitud->rol === 'hotelero' ? 'Datos del hotel' : 'Datos del restaurante' }}
                </div>

                {{-- Datos del hotel --}}
                @if($solicitud->rol === 'hotelero' && isset($hotel))
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Nombre</span>
                            <span class="text-slate-900 font-semibold">{{ $hotel->nombre ?? '—' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Dirección</span>
                            <span class="text-slate-900 font-semibold">{{ $hotel->direccion ?? '—' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Teléfono</span>
                            <span class="text-slate-900 font-semibold">{{ $hotel->telefono ?? '—' }}</span>
                        </div>
                    </div>
                {{-- Datos del restaurante --}}
                @elseif($solicitud->rol === 'restaurantero' && isset($restaurante))
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Nombre</span>
                            <span class="text-slate-900 font-semibold">{{ $restaurante->nombre ?? '—' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Dirección</span>
                            <span class="text-slate-900 font-semibold">{{ $restaurante->direccion ?? '—' }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Teléfono</span>
                            <span class="text-slate-900 font-semibold">{{ $restaurante->telefono ?? '—' }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-slate-500">
                        No se encontró información del negocio asociado.
                    </div>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="p-6 border-t border-slate-200">
            @if($solicitud->estado === 'pendiente')
                <div class="flex flex-col md:flex-row gap-4 md:items-start md:justify-between">

                    {{-- Aprobar --}}
                    <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud->id_usuario) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-700 text-white font-semibold hover:bg-emerald-800 transition">
                            ✅ Aprobar
                        </button>
                    </form>

                    {{-- Rechazar --}}
                    <button onclick="document.getElementById('rechazar-form').style.display = 'block'; document.getElementById('rechazar-form').scrollIntoView({behavior: 'smooth'});"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                        ❌ Rechazar
                    </button>
                </div>

                {{-- Formulario de rechazo que aparece solo si se clickea Rechazar --}}
                <div id="rechazar-form" class="mt-6 hidden p-6 border border-slate-200 rounded-2xl">
                    <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud->id_usuario) }}">
                        @csrf
                        <label for="motivo_rechazo" class="block text-sm font-semibold text-slate-800 mb-2">
                            Motivo de rechazo <span class="text-red-500">*</span>
                        </label>
                        <textarea name="motivo_rechazo" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:ring-2 focus:ring-red-600/25 focus:border-red-700"></textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    onclick="return confirm('¿Seguro que deseas rechazar esta solicitud?')"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                                Confirmar rechazo
                            </button>
                        </div>
                    </form>
                </div>

            @else
                <div class="text-sm text-slate-600">
                    Esta solicitud ya fue atendida ({{ $solicitud->estado }}).
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
