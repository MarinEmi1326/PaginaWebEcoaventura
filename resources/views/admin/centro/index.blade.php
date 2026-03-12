@extends('layouts.admin')

@section('title', 'Gestión de Destinos')

@section('content')

<div class="mb-4">
    <h1 class="ea-page-title mb-1">Gestión de Destinos</h1>
</div>

<div class="ea-card p-0 overflow-hidden">

    {{-- Barra superior --}}
    <div class="p-3 p-md-4 border-bottom d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">

        <div></div>

        <div class="d-flex flex-column flex-md-row gap-2">
            <div style="min-width: 240px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           class="form-control border-start-0 rounded-end-3"
                           placeholder="Buscar destino...">
                </div>
            </div>

            <select class="form-select rounded-3" style="min-width: 120px;">
                <option>Todas</option>
                <option>Activos</option>
                <option>Suspendidos</option>
            </select>

            <select class="form-select rounded-3" style="min-width: 120px;">
                <option>Todos</option>
                <option>Arqueología</option>
                <option>Naturaleza</option>
                <option>Aventura</option>
                <option>Cultura</option>
                <option>Ecoturismo</option>
            </select>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table ea-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Categoría</th>
                    <th>Reportes</th>
                    <th>Estado</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="fw-semibold">Zona Arqueológica de Toniná</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Arqueología</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 3</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Cascadas de Agua Azul</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Naturaleza</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 4</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Selva Lacandona</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Aventura</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 3</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Laguna de Miramar</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Cultura</td>
                    <td><span class="ea-chip red"><i class="bi bi-flag me-1"></i> 5</span></td>
                    <td><span class="ea-chip red">Suspendido</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Cascadas de Misol-Ha</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Ecoturismo</td>
                    <td><span class="ea-chip red"><i class="bi bi-flag me-1"></i> 5</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Ruinas de Bonampak</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Arqueología</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 1</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Yaxchilán</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Naturaleza</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 2</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Cenotes de Ocosingo</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Aventura</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 4</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Mirador del Valle</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Cultura</td>
                    <td><span class="ea-chip gray"><i class="bi bi-flag me-1"></i> 2</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fw-semibold">Museo Comunitario</div>
                        <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                    </td>
                    <td>Ecoturismo</td>
                    <td><span class="ea-chip red"><i class="bi bi-flag me-1"></i> 7</span></td>
                    <td><span class="ea-chip green">Activo</span></td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex align-items-center gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                            <a href="#" class="text-decoration-none" style="color:#e4572e;">
                                <i class="bi bi-slash-circle me-1"></i> Suspender
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-3 px-md-4 py-3 border-top"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.08);">
        <div class="small" style="color: var(--ea-muted);">
            Mostrando 1–10 de 28
        </div>

        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            <button class="btn btn-sm border-0" style="color: var(--ea-muted);">‹</button>
            <button class="btn btn-sm rounded-circle"
                    style="width:32px; height:32px; background: var(--ea-green); color:#fff;">1</button>
            <button class="btn btn-sm border-0" style="color: var(--ea-text);">2</button>
            <button class="btn btn-sm border-0" style="color: var(--ea-text);">3</button>
            <button class="btn btn-sm border-0" style="color: var(--ea-muted);">›</button>
        </div>
    </div>

</div>

@endsection