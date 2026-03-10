@extends('layouts.app')

@section('content')

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">

    <div class="card shadow-sm border-0 text-center p-4" style="max-width:420px; width:100%;">

        <div class="fs-1 mb-3">🌿</div>

        <h1 class="h4 fw-bold">
            ¡Bienvenido, {{ Auth::user()->turista->nombre ?? 'Turista' }}!
        </h1>

        <p class="text-muted small mt-2">
            Has iniciado sesión correctamente como turista.
        </p>

        <div class="mt-4">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn btn-success w-100">
                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>

</div>

@endsection