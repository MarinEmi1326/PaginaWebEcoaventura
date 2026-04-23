@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7 col-xl-6">
            <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5 text-center">
                <div class="mb-3" style="font-size: 3rem;">✅</div>
                <h2 class="fw-bold mb-3">Solicitud enviada correctamente</h2>
                <p class="text-muted mb-4">
                    Tu registro como <strong>Adminstrador de Destino</strong> fue enviado.
                    Ahora tu solicitud será revisada por el administrador.
                </p>
                <a href="{{ route('home') }}" class="btn btn-success px-4 py-2 fw-semibold">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection