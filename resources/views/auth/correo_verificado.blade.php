@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow border-0 text-center p-4">

<h2 class="text-success mb-3">Correo verificado correctamente</h2>

<p>
Tu correo electrónico ha sido confirmado.
</p>

<p>
Ahora tu solicitud será revisada por el administrador de Ecoaventura.
Recibirás un correo cuando tu cuenta sea aprobada o rechazada.
</p>

<a href="{{ route('home') }}" class="btn btn-success mt-3">
Volver al inicio
</a>

</div>
</div>
</div>
</div>
@endsection