@extends('layouts.admin')
@section('title', 'Nuevo Paquete - ' . $destino->nombre)
@section('content')
    <div class="mb-4">
        <a href="{{ route('destinos.paquetes.index', $destino->id_destino) }}" class="text-decoration-none fw-semibold"
            style="color: var(--ea-green);">← Volver a paquetes</a>
        <h1 class="ea-page-title mt-2">Nuevo paquete para: {{ $destino->nombre }}</h1>
    </div>
    <form action="{{ route('destinos.paquetes.store', $destino->id_destino) }}" method="POST">
        @csrf
        <div class="ea-card p-4 mb-4">
            <div class="row g-3">
                <div class="col-12">
                    <label>Nombre del paquete *</label>
                    <input type="text" name="nombre" class="form-control" required
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g,'')">
                </div>
                <div class="col-12">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g,'')"></textarea>
                </div>
                <div class="col-md-4">
                    <label>Precio (MXN)</label>
                    <input type="number" step="0.01" name="precio" class="form-control" min="0"
                        oninput="if(this.value < 0) this.value = 0">
                </div>
                <div class="col-md-4">
                    <label>Tipo de público</label>
                    <select name="tipo_publico" id="tipo_publico" class="form-select">
                        <option value="todo">Todo público</option>
                        <option value="especifico">Rango de edad específico</option>
                    </select>
                </div>
                <div class="col-md-4" id="rango-edad" style="display: none;">
                    <label>Edad mínima</label>
                    <input type="number" name="edad_minima" class="form-control" placeholder="Ej. 18">
                    <label class="mt-2">Edad máxima</label>
                    <input type="number" name="edad_maxima" class="form-control" placeholder="Ej. 60">
                </div>
            </div>
        </div>

        <div class="ea-card p-4 mb-4">
            <div class="fw-semibold mb-3">Actividades incluidas en el paquete</div>
            <div id="actividades-container">
                <div class="row g-3 mb-3 actividad-row">
                    <div class="col-md-5">
                        <select name="actividades[0][id_actividad]" class="form-select" required>
                            <option value="">Seleccionar actividad</option>
                            @foreach ($actividades as $act)
                                <option value="{{ $act->id_actividad }}">{{ $act->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="actividades[0][minimo]" class="form-control"
                            placeholder="Mínimo personas" min="0" oninput="if(this.value < 0) this.value = 0">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="actividades[0][maximo]" class="form-control"
                            placeholder="Máximo personas" min="0" oninput="if(this.value < 0) this.value = 0">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">🗑️</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-success mt-3" onclick="agregarFila()">+ Agregar otra
                actividad</button>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('destinos.paquetes.index', $destino->id_destino) }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn ea-btn-green">Guardar paquete</button>
        </div>
    </form>

    <script>
        document.getElementById('tipo_publico').addEventListener('change', function() {
            document.getElementById('rango-edad').style.display = this.value === 'especifico' ? 'block' : 'none';
        });
        let contadorAct = 1;

        function agregarFila() {
            const container = document.getElementById('actividades-container');
            const newRow = container.children[0].cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(el => el.value = '');
            const index = contadorAct++;
            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            });
            container.appendChild(newRow);
        }

        function eliminarFila(btn) {
            if (document.querySelectorAll('.actividad-row').length > 1) {
                btn.closest('.actividad-row').remove();
            } else {
                alert('Debe haber al menos una actividad');
            }
        }
    </script>
@endsection
