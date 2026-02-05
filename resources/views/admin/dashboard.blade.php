@extends('layouts.admin')

@section('content')
<h1 class="text-4xl font-serif font-semibold text-slate-900">Panel</h1>
<p class="text-slate-500 mt-1">Bienvenido al panel de administración de Ecoaventura</p>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="text-sm text-slate-500">Total Destinos</div>
        <div class="text-4xl font-semibold mt-2">{{ $destinosActivos }}</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="text-sm text-slate-500">Servicios Totales</div>
        <div class="text-4xl font-semibold mt-2">{{ $serviciosTotales }}</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="text-sm text-slate-500">Reservas Totales</div>
        <div class="text-4xl font-semibold mt-2">{{ $reservasTotales }}</div>
    </div>
</div>
@endsection
