@extends('layouts.admin')

@section('title', 'Reportes de Rutas - Admin General')

@section('content')
    <div class="mb-4">
        <h1 class="ea-page-title">Reportes de Rutas</h1>
        <p class="ea-subtitle">Genera reportes en tiempo real y descárgalos en PDF</p>
    </div>

    <div class="ea-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Categoría</label>
                <select id="categoria" class="form-select" disabled>
                    <option value="rutas" selected>Rutas</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tipo de reporte</label>
                <select id="tipo" class="form-select">
                    <option value="por_gestor">Rutas por gestor</option>
                    <option value="por_dificultad">Rutas por dificultad</option>
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

        {{-- Filtros adicionales (siempre visibles, igual que en destinos aparece el de categoría específica) --}}
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label class="form-label fw-bold">Gestor</label>
                <select id="gestor_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($gestores as $g)
                        <option value="{{ $g->id_persona }}">{{ $g->nombre }} {{ $g->apellidos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Dificultad</label>
                <select id="dificultad" class="form-select">
                    <option value="">Todas</option>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
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
                        <th>Gestor</th>
                        <th>Dificultad</th>
                        <th>Estado</th>
                        <th>Fecha creación</th>
                        <th>Duración</th>
                        <th>Distancia (km)</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <tr>
                        <td colspan="7" class="text-center">Seleccione un reporte y presione Filtrar</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tipoSelect = document.getElementById('tipo');
        const gestorSelect = document.getElementById('gestor_id');
        const dificultadSelect = document.getElementById('dificultad');
        const estadoSelect = document.getElementById('estado');
        const fechaDesde = document.getElementById('fecha_desde');
        const fechaHasta = document.getElementById('fecha_hasta');
        const btnFiltrar = document.getElementById('btn-filtrar');
        const btnLimpiar = document.getElementById('btn-limpiar');
        const btnPdf = document.getElementById('btn-pdf');
        const tablaHeaders = document.getElementById('tabla-headers');
        const tablaBody = document.getElementById('tabla-body');

        function cargarDatos() {
            const params = {
                tipo: tipoSelect.value,
                gestor_id: gestorSelect.value,
                dificultad: dificultadSelect.value,
                estado: estadoSelect.value,
                fecha_desde: fechaDesde.value,
                fecha_hasta: fechaHasta.value
            };
            fetch('{{ route('admin.reportes.rutas.data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(params)
                })
                .then(response => response.json())
                .then(data => actualizarTabla(data.data))
                .catch(error => console.error('Error:', error));
        }

        function actualizarTabla(data) {
            tablaBody.innerHTML = '';
            // Los headers son fijos (no dependen del tipo)
            tablaHeaders.innerHTML = `
            <tr>
                <th>Nombre</th>
                <th>Gestor</th>
                <th>Dificultad</th>
                <th>Estado</th>
                <th>Fecha creación</th>
                <th>Duración</th>
                <th>Distancia (km)</th>
            </tr>
        `;

            if (data.length === 0) {
                const row = tablaBody.insertRow();
                const cell = row.insertCell(0);
                cell.colSpan = 7;
                cell.className = 'text-center text-muted';
                cell.textContent = 'No hay datos para mostrar.';
                return;
            }

            data.forEach(item => {
                const row = tablaBody.insertRow();
                row.insertCell().textContent = item.ruta_nombre;
                row.insertCell().textContent = `${item.gestor_nombre} ${item.gestor_apellidos}`;
                row.insertCell().textContent = ucfirst(item.dificultad);
                row.insertCell().innerHTML = item.activo === 'activo' ?
                    '<span style="color:#28a745;"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Activo</span>' :
                    '<span style="color:#6c757d;"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Inactivo</span>';
                row.insertCell().textContent = item.fecha_creacion ? new Date(item.fecha_creacion)
                    .toLocaleDateString() : 'N/A';
                row.insertCell().textContent = item.duracion_estimada || 'N/A';
                row.insertCell().textContent = item.distancia_km ? `${item.distancia_km} km` : 'N/A';
            });
        }

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function limpiarFiltros() {
            gestorSelect.value = '';
            dificultadSelect.value = '';
            estadoSelect.value = '';
            fechaDesde.value = '';
            fechaHasta.value = '';
            cargarDatos();
        }

        function descargarPDF() {
            const params = new URLSearchParams({
                tipo: tipoSelect.value,
                gestor_id: gestorSelect.value,
                dificultad: dificultadSelect.value,
                estado: estadoSelect.value,
                fecha_desde: fechaDesde.value,
                fecha_hasta: fechaHasta.value
            });
            window.location.href = '{{ route('admin.reportes.rutas.pdf') }}?' + params.toString();
        }

        btnFiltrar.addEventListener('click', cargarDatos);
        btnLimpiar.addEventListener('click', limpiarFiltros);
        btnPdf.addEventListener('click', descargarPDF);

        // Cargar datos iniciales
        cargarDatos();
    </script>
@endpush
