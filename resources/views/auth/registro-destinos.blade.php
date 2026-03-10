@extends('layouts.app')

{{-- @php $hideNavbar = true; @endphp --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7 col-xl-6">

            <div class="mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    ← Volver al inicio
                </a>
            </div>

            <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5">
                <h2 class="fw-bold mb-2">Registro — Admin Destinos</h2>
                <p class="text-muted mb-4">
                    Completa tus datos para enviar tu solicitud de registro.
                </p>

                <div class="alert alert-light border rounded-3 mb-4">
                    <strong>Importante:</strong> tu cuenta quedará pendiente de aprobación
                    hasta que el administrador la revise.
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.destinos') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre *</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            class="form-control"
                            placeholder="Tu nombre"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="apaterno" class="form-label fw-semibold">Apellido paterno *</label>
                        <input
                            type="text"
                            id="apaterno"
                            name="apaterno"
                            value="{{ old('apaterno') }}"
                            class="form-control"
                            placeholder="Apellido paterno"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="amaterno" class="form-label fw-semibold">Apellido materno</label>
                        <input
                            type="text"
                            id="amaterno"
                            name="amaterno"
                            value="{{ old('amaterno') }}"
                            class="form-control"
                            placeholder="Apellido materno ">
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-semibold">Teléfono *</label>
                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono') }}"
                            class="form-control"
                            maxlength="10"
                            placeholder="9611234567"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo electrónico *</label>
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            value="{{ old('correo') }}"
                            class="form-control"
                            placeholder="tu@email.com"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Contraseña *</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña *</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="••••••••"
                            required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        Enviar solicitud
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection