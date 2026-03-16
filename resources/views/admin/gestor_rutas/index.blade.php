@extends('layouts.admin')

@section('title', 'Panel de Rutas')

@section('content')

{{-- Mensaje de éxito al crear/eliminar --}}
@if (session('success'))
    <div class="alert alert-success rounded-3 mb-4" id="alerta-exito">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const a = document.getElementById('alerta-exito');
            if (a) a.remove();
        }, 3000);
    </script>
@endif

{{-- Encabezado --}}
<div class="mb-4">
    <h1 class="ea-page-title mb-1">Mis Rutas</h1>
    <p class="ea-subtitle mb-0">Administra y consulta tus rutas registradas.</p>
</div>

{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="ea-card ea-card-tight text-center">
            {{-- $total viene del controlador: $rutas->count() --}}
            <div class="ea-summary-number">{{ $total }}</div>
            <div class="ea-summary-label">Rutas registradas</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="ea-card ea-card-tight text-center">
            {{-- Contamos cuántas tienen activo = 'activo' --}}
            <div class="ea-summary-number is-approved">
                {{ $rutas->where('activo', 'activo')->count() }}
            </div>
            <div class="ea-summary-label">Rutas activas</div>
        </div>
    </div>
</div>

{{-- Botón nueva ruta --}}
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('rutas.create') }}" class="btn ea-btn-green rounded-3">
        <i class="bi bi-plus-lg me-1"></i> Nueva Ruta
    </a>
</div>

{{-- Listado de rutas --}}
<div class="ea-card p-0 overflow-hidden">

    <div class="p-4 border-bottom"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.18);">
        <div class="fw-semibold" style="font-size: 1.05rem;">Mis rutas registradas</div>
    </div>

    <div class="p-3 p-md-4">

        {{-- Si tiene rutas las muestra, si no muestra un mensaje vacío --}}
        @forelse ($rutas as $ruta)

            <div class="ea-card p-4 mb-3">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                    {{-- Información de la ruta --}}
                  <div class="fw-semibold" style="font-size: 1.05rem;">
                        {{ $ruta->nombre }}
                    </div>

                    {{-- Mostrar motivo si la ruta está inhabilitada por un destino suspendido --}}
                    @if ($ruta->activo === 'inactivo' && $ruta->motivo_inactivo)
                        <div class="small mt-1 d-flex align-items-center gap-1"
                            style="color: #E65100;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            {{ $ruta->motivo_inactivo }}
                        </div>
                    @endif

                    <div class="small mt-1" style="color: var(--ea-muted);">

                            {{-- Duración si existe --}}
                            @if ($ruta->duracion_estimada)
                                <span class="me-2">
                                    <i class="bi bi-clock me-1"></i>{{ $ruta->duracion_estimada }}
                                </span>
                            @endif

                            {{-- Dificultad --}}
                            <span class="me-2">
                                <i class="bi bi-bar-chart me-1"></i>{{ ucfirst($ruta->dificultad) }}
                            </span>

                            {{-- Fecha de creación --}}
                            <span>
                                <i class="bi bi-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($ruta->fecha_creacion)->format('d/m/Y') }}
                            </span>

                        </div>

                        {{-- Fechas de operación si las tiene --}}
                        @if ($ruta->fecha_inicio_operacion && $ruta->fecha_fin_operacion)
                            <div class="small mt-1" style="color: var(--ea-muted);">
                                <i class="bi bi-calendar-range me-1"></i>
                                Disponible del
                                {{ \Carbon\Carbon::parse($ruta->fecha_inicio_operacion)->format('d/m/Y') }}
                                al
                                {{ \Carbon\Carbon::parse($ruta->fecha_fin_operacion)->format('d/m/Y') }}
                            </div>
                        @endif
                    </div>

                    {{-- Acciones --}}
                    <div class="d-flex align-items-center gap-3 flex-wrap ms-lg-auto">

                        {{-- Badge de estado --}}
                        @if ($ruta->activo === 'activo')
                            <span class="ea-status approved">Activa</span>
                        @else
                            <span class="ea-status pending">Inactiva</span>
                        @endif

                        {{-- Botones editar y eliminar --}}
                        <div class="ea-actions">

                            <a href="{{ route('rutas.edit', $ruta->id_ruta) }}" class="ea-action-btn edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('rutas.destroy', $ruta->id_ruta) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta ruta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="ea-action-btn delete border-0 bg-transparent p-0"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        @empty

            {{-- Mensaje cuando no hay rutas --}}
            <div class="ea-card text-center py-5">
                <i class="bi bi-signpost-2 text-muted" style="font-size: 2.5rem;"></i>
                <div class="mt-3 fw-semibold text-muted">Aún no tienes rutas registradas.</div>
                <a href="{{ route('rutas.create') }}" class="btn ea-btn-green mt-3 rounded-3">
                    <i class="bi bi-plus-lg me-1"></i> Crear mi primera ruta
                </a>
            </div>

        @endforelse

    </div>
</div>

@endsection