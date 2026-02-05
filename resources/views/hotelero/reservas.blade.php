@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-2xl font-semibold mb-6">Reservas</h1>

    @forelse($reservas as $reserva)
        <div class="bg-white shadow rounded p-4 mb-4">
            <p><strong>ID Reserva:</strong> {{ $reserva->id_reserva }}</p>
            <p><strong>Estado:</strong> {{ $reserva->estado }}</p>
            <p><strong>Entrada:</strong> {{ $reserva->fecha_entrada }}</p>
            <p><strong>Salida:</strong> {{ $reserva->fecha_salida }}</p>
        </div>
    @empty
        <p>No hay reservas registradas.</p>
    @endforelse
</div>
@endsection
