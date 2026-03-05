@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-sm border-0 p-5 text-center" style="max-width: 450px; width: 100%;">

        <div class="fs-1 mb-3">🏔️</div>

        <h1 class="h4 fw-semibold text-dark">
            ¡Bienvenido, {{ Auth::user()->adminDestinos->nombre ?? 'Admin Destinos' }}!
        </h1>

        <p class="text-muted mt-2 small">
            Has iniciado sesión correctamente como administrador de destinos.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-primary w-100 rounded-pill">
                Cerrar sesión
            </button>
        </form>

    </div>
</div>
@endsection