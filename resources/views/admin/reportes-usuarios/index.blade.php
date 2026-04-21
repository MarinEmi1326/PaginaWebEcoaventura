@extends('layouts.admin')

@section('title', 'Reportes de Usuarios')

@section('content')
<div class="mb-4">
    <h1 class="ea-page-title">Reportes de Usuarios</h1>
    <p class="ea-subtitle">Genera reportes en tiempo real y descárgalos en PDF</p>
</div>

<div class="ea-card p-4 mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Tipo de reporte</label>
            <select id="tipo" class="form-select">
                <option value="por_rol">Usuarios por Rol</option>
                <option value="por_estado">Usuarios por Estado</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Rol</label>
            <select id="filtro_rol" class="form-select">
                <option value="">Todos</option>
                <option value="admin_destinos">Admin. de Destinos</option>
                <option value="gestor_rutas">Gestor de Rutas</option>
                <option value="turista">Turista</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Estado</label>
            <select id="filtro_estado" class="form-select">
                <option value="">Todos</option>
                <option value="aprobado">Aprobado</option>
                <option value="pendiente">Pendiente</option>
                <option value="rechazado">Rechazado</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Activo</label>
            <select id="filtro_activo" class="form-select">
                <option value="">Todos</option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <button id="btn-filtrar" class="btn ea-btn-green">Filtrar</button>
        <button id="btn-limpiar" class="btn btn-light border">Limpiar</button>
        <button id="btn-pdf" class="btn btn-outline-secondary">Descargar PDF</button>
    </div>
</div>

<div class="ea-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table ea-table align-middle mb-0">
            <thead id="tabla-headers">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Activo</th>
                    <th>Fecha solicitud</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <tr><td colspan="6" class="text-center">Seleccione un tipo y presione Filtrar</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const tipoSelect      = document.getElementById('tipo');
    const filtroRol       = document.getElementById('filtro_rol');
    const filtroEstado    = document.getElementById('filtro_estado');
    const filtroActivo    = document.getElementById('filtro_activo');
    const tablaBody       = document.getElementById('tabla-body');
    const tablaHeaders    = document.getElementById('tabla-headers');

    const rolLabel = {
        admin_destinos: 'Admin. de Destinos',
        gestor_rutas:   'Gestor de Rutas',
        turista:        'Turista'
    };

    function cargarDatos() {
        const params = {
            tipo:   tipoSelect.value,
            rol:    filtroRol.value,
            estado: filtroEstado.value,
            activo: filtroActivo.value
        };

        fetch('{{ route("admin.reportes.usuarios.data") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(params)
        })
        .then(r => r.json())
        .then(res => renderTabla(res.data, params.tipo))
        .catch(err => console.error('Error:', err));
    }

    function renderTabla(data, tipo) {
        tablaBody.innerHTML = '';

        if (!data || data.length === 0) {
            tablaBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No hay datos para mostrar.</td></tr>`;
            return;
        }

        data.forEach(item => {
            const fecha = item.fecha_solicitud
                ? new Date(item.fecha_solicitud).toLocaleDateString('es-MX')
                : '—';

            const activoBadge = item.activo == 1
                ? '<span class="ea-chip green">Sí</span>'
                : '<span class="ea-chip red">No</span>';

            const estadoBadge = {
                aprobado:  '<span class="ea-chip green">Aprobado</span>',
                pendiente: '<span class="ea-chip blue">Pendiente</span>',
                rechazado: '<span class="ea-chip red">Rechazado</span>',
            }[item.estado] ?? `<span class="ea-chip gray">${item.estado}</span>`;

            const fila = `
                <tr>
                    <td class="fw-semibold">${item.nombre_completo}</td>
                    <td style="color:var(--ea-muted)">${item.correo}</td>
                    <td>${rolLabel[item.rol] ?? item.rol}</td>
                    <td>${estadoBadge}</td>
                    <td>${activoBadge}</td>
                    <td>${fecha}</td>
                </tr>`;
            tablaBody.innerHTML += fila;
        });
    }

    function limpiar() {
        filtroRol.value    = '';
        filtroEstado.value = '';
        filtroActivo.value = '';
        cargarDatos();
    }

    function descargarPDF() {
        const params = new URLSearchParams({
            tipo:   tipoSelect.value,
            rol:    filtroRol.value,
            estado: filtroEstado.value,
            activo: filtroActivo.value
        });
        window.location.href = '{{ route("admin.reportes.usuarios.pdf") }}?' + params.toString();
    }

    document.getElementById('btn-filtrar').addEventListener('click', cargarDatos);
    document.getElementById('btn-limpiar').addEventListener('click', limpiar);
    document.getElementById('btn-pdf').addEventListener('click', descargarPDF);
    tipoSelect.addEventListener('change', cargarDatos);

    cargarDatos();
</script>
@endpush