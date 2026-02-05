@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-2xl font-semibold mb-6">Habitaciones</h1>

    @forelse($habitaciones as $habitacion)
        <div class="bg-white shadow rounded p-4 mb-4">
            <p><strong>Tipo:</strong> {{ $habitacion->tipo }}</p>
            <p><strong>Capacidad:</strong> {{ $habitacion->capacidad }}</p>
            <p><strong>Precio:</strong> ${{ $habitacion->precio }}</p>
            <p><strong>Estado:</strong> {{ $habitacion->estado }}</p>
        </div>
    @empty
        <p>No hay habitaciones registradas.</p>
    @endforelse
</div>
@endsection
