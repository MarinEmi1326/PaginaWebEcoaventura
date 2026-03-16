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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: #F6F6F2;">
                
                {{-- Banner --}}
                <div style="height: 120px; background: linear-gradient(135deg, #0F5A3A, #0B4A30);"></div>

                <div class="card-body px-4 pb-4" style="margin-top: -50px;">
                    
                    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Identidad --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="position-relative">
                                @if(auth()->user()->foto_perfil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" 
                                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover;" 
                                         class="border border-4 border-white shadow-sm">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-secondary-subtle border border-4 border-white shadow-sm" 
                                         style="width: 110px; height: 110px; border-radius: 50%; font-size: 2.5rem; font-weight: 800; color: #1F2A24;">
                                        {{ strtoupper(substr(auth()->user()->perfil->nombre ?? 'D', 0, 1)) }}
                                    </div>
                                @endif
                                <label for="foto_perfil" class="btn btn-dark btn-sm position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; cursor: pointer;">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" id="foto_perfil" name="foto_perfil" hidden accept="image/*">
                            </div>

                            <div class="ms-3 mt-5">
                                <h2 class="h3 mb-0 fw-bold" style="font-family: Georgia, serif;">{{ auth()->user()->perfil->nombre }} {{ auth()->user()->perfil->apaterno }}</h2>
                                <span class="badge rounded-pill" style="background-color: #DFE6DE; color: #1F2A24;">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Administrador de Destinos
                                </span>
                            </div>
                        </div>

                        {{-- Datos Personales --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="bi bi-person-badge me-2"></i>Información Personal</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre</label>
                                <input type="text" name="nombre" class="form-control border-0 shadow-sm py-2" value="{{ old('nombre', auth()->user()->perfil->nombre) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Apellido Paterno</label>
                                <input type="text" name="apaterno" class="form-control border-0 shadow-sm py-2" value="{{ old('apaterno', auth()->user()->perfil->apaterno) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Apellido Materno</label>
                                <input type="text" name="amaterno" class="form-control border-0 shadow-sm py-2" value="{{ old('amaterno', auth()->user()->perfil->amaterno) }}">
                            </div>
                        </div>

                        {{-- Datos de Contacto y Acceso --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono de contacto</label>
                                <input type="text" name="telefono" class="form-control border-0 shadow-sm py-2" value="{{ old('telefono', auth()->user()->perfil->telefono) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo de Inicio de Sesión</label>
                                <input type="email" name="correo" class="form-control border-0 shadow-sm py-2" value="{{ old('correo', auth()->user()->correo) }}" required>
                            </div>
                        </div>

                        {{-- Sección de Redes Sociales --}}
                        <div class="mt-5 mb-3 border-bottom pb-2">
                            <h5 class="fw-bold" style="color: #0F5A3A;"><i class="bi bi-share me-2"></i>Presencia Digital</h5>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"><i class="bi bi-facebook me-1"></i> FACEBOOK URL</label>
                                <input type="url" name="facebook_url" class="form-control border-0 shadow-sm py-2" placeholder="https://..." value="{{ old('facebook_url', auth()->user()->perfil->facebook_url) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"><i class="bi bi-instagram me-1"></i> INSTAGRAM URL</label>
                                <input type="url" name="instagram_url" class="form-control border-0 shadow-sm py-2" placeholder="https://..." value="{{ old('instagram_url', auth()->user()->perfil->instagram_url) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"><i class="bi bi-tiktok me-1"></i> TIKTOK URL</label>
                                <input type="url" name="tiktok_url" class="form-control border-0 shadow-sm py-2" placeholder="https://..." value="{{ old('tiktok_url', auth()->user()->perfil->tiktok_url) }}">
                            </div>
                        </div>

                        {{-- Sección de Seguridad --}}
                        <div class="mt-5 mb-3 border-bottom pb-2">
                            <h5 class="fw-bold" style="color: #0F5A3A;"><i class="bi bi-lock me-2"></i>Seguridad</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">NUEVA CONTRASEÑA</label>
                                <input type="password" name="password" class="form-control border-0 shadow-sm py-2" placeholder="Dejar vacío para no cambiar">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">CONFIRMAR CONTRASEÑA</label>
                                <input type="password" name="password_confirmation" class="form-control border-0 shadow-sm py-2" placeholder="Repite la contraseña">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-lg px-5 text-white shadow-sm" style="background-color: #0F5A3A; border-radius: 10px; font-weight: 600;">
                                <i class="bi bi-person-check me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection