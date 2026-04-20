@extends('layouts.admin')

@section('title', 'Reportes - Admin Destinos')

@section('content')
<div class="mb-4">
    <h1 class="ea-page-title">Reportes de Destinos y Paquetes</h1>
    <p class="ea-subtitle">Genera reportes en tiempo real y descárgalos en PDF</p>
</div>

<div class="ea-card p-4 mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Categoría</label>
            <select id="categoria" class="form-select">
                <option value="destinos">Destinos</option>
                <option value="paquetes">Paquetes</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Tipo de reporte</label>
            <select id="tipo" class="form-select">
                <option value="por_categoria">Destinos por categoría</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Estado</label>
            <select id="estado" class="form-select">
                <option value="">Todos</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Fecha desde</label>
            <input type="date" id="fecha_desde" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Fecha hasta</label>
            <input type="date" id="fecha_hasta" class="form-control">
        </div>
    </div>
    <div class="row g-3 mt-2" id="filtro-categoria-container" style="display: none;">
        <div class="col-md-3">
            <label class="form-label fw-bold">Categoría específica</label>
            <select id="categoria_id" class="form-select">
                <option value="">Todas</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                @endforeach
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
        <table class="table ea-table align-middle mb-0" id="tabla-reporte">
            <thead id="tabla-headers">
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Fecha creación</th>
                    <th>Categorías</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <tr><td colspan="4" class="text-center">Seleccione un reporte y presione Filtrar</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const categoriaSelect = document.getElementById('categoria');
    const tipoSelect = document.getElementById('tipo');
    const estadoSelect = document.getElementById('estado');
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    const categoriaId = document.getElementById('categoria_id');
    const filtroCategoriaContainer = document.getElementById('filtro-categoria-container');
    const btnFiltrar = document.getElementById('btn-filtrar');
    const btnLimpiar = document.getElementById('btn-limpiar');
    const btnPdf = document.getElementById('btn-pdf');
    const tablaHeaders = document.getElementById('tabla-headers');
    const tablaBody = document.getElementById('tabla-body');

    function actualizarTipos() {
        const categoria = categoriaSelect.value;
        tipoSelect.innerHTML = '';
        if (categoria === 'destinos') {
            tipoSelect.innerHTML = '<option value="por_categoria">Destinos por categoría</option>';
            filtroCategoriaContainer.style.display = 'block';
        } else {
            tipoSelect.innerHTML = `
                <option value="con_paquetes">Destinos con paquetes</option>
                <option value="sin_paquetes">Destinos sin paquetes</option>
            `;
            filtroCategoriaContainer.style.display = 'none';
        }
        limpiarFiltros();
        cargarDatos();
    }

    function cargarDatos() {
        const params = {
            categoria: categoriaSelect.value,
            tipo: tipoSelect.value,
            estado: estadoSelect.value,
            fecha_desde: fechaDesde.value,
            fecha_hasta: fechaHasta.value,
            categoria_id: categoriaId.value
        };
        fetch('{{ route("reportes.destinos.data") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(params)
        })
        .then(response => response.json())
        .then(data => actualizarTabla(data.data, params.categoria, params.tipo))
        .catch(error => console.error('Error:', error));
    }

    function actualizarTabla(data, categoria, tipo) {
        tablaBody.innerHTML = '';
        let headers = [];
        if (categoria === 'destinos') {
            headers = ['Nombre', 'Estado', 'Fecha creación', 'Categorías'];
        } else {
            if (tipo === 'con_paquetes') {
                headers = ['Nombre', 'Estado', 'Fecha creación', 'Total paquetes', 'Paquetes'];
            } else {
                headers = ['Nombre', 'Estado', 'Fecha creación'];
            }
        }
        tablaHeaders.innerHTML = '';
        const headerRow = document.createElement('tr');
        headers.forEach(h => {
            const th = document.createElement('th');
            th.textContent = h;
            headerRow.appendChild(th);
        });
        tablaHeaders.appendChild(headerRow);

        if (data.length === 0) {
            const row = tablaBody.insertRow();
            const cell = row.insertCell(0);
            cell.colSpan = headers.length;
            cell.className = 'text-center text-muted';
            cell.textContent = 'No hay datos para mostrar.';
            return;
        }

        data.forEach(item => {
            const row = tablaBody.insertRow();
            row.insertCell().textContent = item.destino_nombre;
            row.insertCell().textContent = item.activo === 'activo' ? 'Activo' : 'Inactivo';
            row.insertCell().textContent = item.fecha_creacion ? new Date(item.fecha_creacion).toLocaleDateString() : 'N/A';
            if (categoria === 'destinos') {
                row.insertCell().textContent = item.categorias || 'Sin categoría';
            } else if (tipo === 'con_paquetes') {
                row.insertCell().textContent = item.total_paquetes;
                row.insertCell().textContent = item.paquetes_nombres || '';
            }
        });
    }

    function limpiarFiltros() {
        estadoSelect.value = '';
        fechaDesde.value = '';
        fechaHasta.value = '';
        categoriaId.value = '';
        cargarDatos();
    }

    function descargarPDF() {
        const params = new URLSearchParams({
            categoria: categoriaSelect.value,
            tipo: tipoSelect.value,
            estado: estadoSelect.value,
            fecha_desde: fechaDesde.value,
            fecha_hasta: fechaHasta.value,
            categoria_id: categoriaId.value
        });
        window.location.href = '{{ route("reportes.destinos.pdf") }}?' + params.toString();
    }

    categoriaSelect.addEventListener('change', actualizarTipos);
    tipoSelect.addEventListener('change', cargarDatos);
    btnFiltrar.addEventListener('click', cargarDatos);
    btnLimpiar.addEventListener('click', limpiarFiltros);
    btnPdf.addEventListener('click', descargarPDF);

    actualizarTipos();
</script>
@endpush