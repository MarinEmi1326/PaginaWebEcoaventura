@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            {{-- Errores de Validación --}}
            @if($errors->any())
                <div class="alert alert-danger shadow-sm mb-4" style="border-radius: 12px;">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Notificación de éxito --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-stars me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: #F6F6F2;">
                
                {{-- Banner con color representativo del Turista (Verde Naturaleza) --}}
                <div style="height: 120px; background: linear-gradient(135deg, #0F5A3A, #0B4A30);"></div>

                <div class="card-body px-4 pb-4" style="margin-top: -50px;">
                    
                    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Foto y Encabezado --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="position-relative">
                                @if(auth()->user()->foto_perfil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" 
                                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover;" 
                                         class="border border-4 border-white shadow-sm">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-secondary-subtle border border-4 border-white shadow-sm" 
                                         style="width: 110px; height: 110px; border-radius: 50%; font-size: 2.5rem; font-weight: 800; color: #1F2A24;">
                                        {{ strtoupper(substr(auth()->user()->perfil->nombre ?? 'T', 0, 1)) }}
                                    </div>
                                @endif
                                
                                <label for="foto_perfil" class="btn btn-dark btn-sm position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; cursor: pointer;">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" id="foto_perfil" name="foto_perfil" hidden accept="image/*">
                            </div>

                            <div class="ms-3 mt-5">
                                <h2 class="h3 mb-0 fw-bold" style="font-family: Georgia, serif;">¡Hola, {{ auth()->user()->perfil->nombre }}!</h2>
                                <span class="badge rounded-pill" style="background-color: #DFE6DE; color: #0B4A30;">
                                    <i class="bi bi-person-walking me-1"></i> Perfil de Explorador
                                </span>
                            </div>
                        </div>

                        {{-- Datos Personales --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="bi bi-person-lines-fill me-2"></i>Información Personal</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre(s)</label>
                                <input type="text" name="nombre" class="form-control border-0 shadow-sm py-2" 
                                       style="border-radius: 10px;" value="{{ old('nombre', auth()->user()->perfil->nombre) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Apellido Paterno</label>
                                <input type="text" name="apaterno" class="form-control border-0 shadow-sm py-2" 
                                       style="border-radius: 10px;" value="{{ old('apaterno', auth()->user()->perfil->apaterno) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Apellido Materno</label>
                                <input type="text" name="amaterno" class="form-control border-0 shadow-sm py-2" 
                                       style="border-radius: 10px;" value="{{ old('amaterno', auth()->user()->perfil->amaterno) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono de contacto</label>
                                <input type="text" name="telefono" class="form-control border-0 shadow-sm py-2" 
                                       style="border-radius: 10px;" value="{{ old('telefono', auth()->user()->perfil->telefono) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control border-0 shadow-sm py-2" 
                                       style="border-radius: 10px;" value="{{ old('correo', auth()->user()->correo) }}" required>
                            </div>
                        </div>

                        {{-- Seguridad --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 mt-4"><i class="bi bi-key-fill me-2"></i>Cambiar Contraseña</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">CAMBIAR CONTRASEÑA</label>
                                <input type="password" name="password" class="form-control border-0 shadow-sm py-2" placeholder="Nueva contraseña (opcional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">CONFIRMAR CONTRASEÑA</label>
                                <input type="password" name="password_confirmation" class="form-control border-0 shadow-sm py-2" placeholder="Confirmar Contraseña">
                            </div>
                        </div>

                        {{-- Botón de Guardar --}}
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-lg px-5 text-white shadow-sm" 
                                    style="background-color: #0F5A3A; border-radius: 10px; font-weight: 600;">
                                <i class="bi bi-person-check me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> 
                    Tus datos personales solo son visibles para los administradores cuando realizas una reserva.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection