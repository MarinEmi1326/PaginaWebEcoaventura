@extends('layouts.admin')
@section('title', 'Paquetes de ' . $destino->nombre)
@section('content')
<div class="mb-4">
    <a href="{{ route('misdestinos.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">← Mis destinos</a>
    <h1 class="ea-page-title mt-2">Paquetes de: {{ $destino->nombre }}</h1>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('destinos.paquetes.create', $destino->id_destino) }}" class="btn ea-btn-green">+ Nuevo paquete</a>
</div>
@forelse($paquetes as $paq)
<div class="ea-card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5 class="fw-bold">{{ $paq->nombre }}</h5>
            <p class="text-muted small">{{ $paq->descripcion }}</p>
            <div><strong>Precio:</strong> ${{ number_format($paq->precio, 2) }}</div>
            <div><strong>Público:</strong> {{ $paq->tipo_publico == 'todo' ? 'Todo público' : "Edad {$paq->edad_minima} - {$paq->edad_maxima} años" }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('destinos.paquetes.edit', [$destino->id_destino, $paq->id_paquete]) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form action="{{ route('destinos.paquetes.destroy', [$destino->id_destino, $paq->id_paquete]) }}" method="POST" onsubmit="return confirm('¿Eliminar este paquete?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="ea-card text-center py-5">
    No hay paquetes para este destino. <a href="{{ route('destinos.paquetes.create', $destino->id_destino) }}">Crear primero</a>
</div>
@endforelse
@endsection