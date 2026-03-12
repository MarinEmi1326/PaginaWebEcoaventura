@extends('layouts.admin')

@section('title', 'Detalle de Reportes')

@section('content')

<div class="mb-4">

    <a href="{{ route('admin.destino') }}"
       class="text-decoration-none fw-semibold"
       style="color: var(--ea-green);">
        ← Volver al listado
    </a>

</div>


<div class="ea-card p-0 overflow-hidden">

    {{-- Encabezado destino --}}
    <div class="p-4 border-bottom"
         style="border-color: var(--ea-line) !important;">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h4 class="fw-semibold mb-1">
                    Cascadas de Misol-Ha
                </h4>

                <div class="small" style="color: var(--ea-muted);">
                    Ecoturismo · Ocosingo, Chiapas
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">

                <span class="ea-chip green">
                    Activo
                </span>

                <button class="btn btn-sm"
                        style="background:#e4572e;color:white;">
                    <i class="bi bi-slash-circle me-1"></i>
                    Suspender
                </button>

            </div>

        </div>

    </div>


    {{-- Estadísticas --}}
    <div class="p-4 border-bottom"
         style="border-color: var(--ea-line) !important;">

        <div class="row g-3">

            <div class="col-md-4">
                <div class="ea-stat-card text-center p-3">
                    <h3 class="fw-bold mb-0">5</h3>
                    <small style="color:var(--ea-muted)">
                        Reportes totales
                    </small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="ea-stat-card text-center p-3">
                    <h3 class="fw-bold text-danger mb-0">3</h3>
                    <small style="color:var(--ea-muted)">
                        Pendientes
                    </small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="ea-stat-card text-center p-3">
                    <h3 class="fw-bold text-success mb-0">2</h3>
                    <small style="color:var(--ea-muted)">
                        Revisados
                    </small>
                </div>
            </div>

        </div>

    </div>


    {{-- Tabla reportes --}}
    <div class="p-4">

        <h6 class="fw-semibold mb-3">
            Detalle de Reportes
        </h6>

        <div class="table-responsive">

            <table class="table ea-table align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Motivo</th>
                        <th>Reportado por</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>Información incorrecta</td>
                        <td>Usuario 1</td>
                        <td>2026-01-10</td>

                        <td>
                            <span class="ea-chip green">
                                Revisado
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="#"
                               class="text-decoration-none">
                               <i class="bi bi-eye"></i>
                               Ver
                            </a>

                        </td>
                    </tr>


                    <tr>
                        <td>2</td>
                        <td>Contenido ofensivo</td>
                        <td>Ana López</td>
                        <td>2026-01-11</td>

                        <td>
                            <span class="ea-chip gray">
                                Pendiente
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="#"
                               class="text-decoration-none">
                               <i class="bi bi-eye"></i>
                               Ver
                            </a>

                        </td>
                    </tr>


                    <tr>
                        <td>3</td>
                        <td>Fotos inapropiadas</td>
                        <td>Roberto Sánchez</td>
                        <td>2026-01-12</td>

                        <td>
                            <span class="ea-chip gray">
                                Pendiente
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="#"
                               class="text-decoration-none">
                               <i class="bi bi-eye"></i>
                               Ver
                            </a>

                        </td>
                    </tr>


                    <tr>
                        <td>4</td>
                        <td>Spam o publicidad</td>
                        <td>Marta Cruz</td>
                        <td>2026-02-13</td>

                        <td>
                            <span class="ea-chip green">
                                Revisado
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="#"
                               class="text-decoration-none">
                               <i class="bi bi-eye"></i>
                               Ver
                            </a>

                        </td>
                    </tr>


                    <tr>
                        <td>5</td>
                        <td>Ubicación errónea</td>
                        <td>Jorge Díaz</td>
                        <td>2026-02-14</td>

                        <td>
                            <span class="ea-chip gray">
                                Pendiente
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="#"
                               class="text-decoration-none">
                               <i class="bi bi-eye"></i>
                               Ver
                            </a>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection