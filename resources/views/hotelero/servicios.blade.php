@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-2xl font-semibold mb-6">Servicios</h1>

    @forelse($servicios as $servicio)
        <div class="bg-white shadow rounded p-4 mb-4">
            <p>{{ $servicio->nombre ?? 'Servicio' }}</p>
        </div>
    @empty
        <p>No hay servicios asignados.</p>
    @endforelse
</div>
@endsection
