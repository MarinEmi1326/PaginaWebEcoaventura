@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Respaldo de Información</h1>
            <a href="{{ route('admin.respaldos.generar') }}" class="btn btn-success">
                <i class="bi bi-database"></i> Generar Respaldo
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tamaño</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($archivos as $archivo)
                                <tr>
                                    <td>{{ $archivo['fecha'] }}</td>
                                    <td>{{ $archivo['tamaño'] }}</td>
                                    <td><span class="badge"
                                            style="background-color: #10b981; font-size: 0.7rem; padding: 4px 8px; border-radius: 20px;">
                                            <i class="bi bi-check-circle me-1" style="font-size: 0.7rem;"></i> completado
                                        </span></td>
                                    <td>
                                        <a href="{{ route('admin.respaldos.descargar', $archivo['nombre']) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i> Descargar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay respaldos generados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Script para auto-ocultar alertas después de 3 segundos --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-ocultar alerta de éxito
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.classList.remove('show');
                    setTimeout(function() {
                        successAlert.remove();
                    }, 150);
                }, 3000); // 3 segundos
            }

            // Auto-ocultar alerta de error (5 segundos)
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.classList.remove('show');
                    setTimeout(function() {
                        errorAlert.remove();
                    }, 150);
                }, 5000); // 5 segundos
            }
        });
    </script>
