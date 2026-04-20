@extends('layouts.admin')
@section('title', 'Editar Paquete - ' . $destino->nombre)
@section('content')
<div class="mb-4">
    <a href="{{ route('destinos.paquetes.index', $destino->id_destino) }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">← Volver a paquetes</a>
    <h1 class="ea-page-title mt-2">Editar paquete: {{ $paquete->nombre }}</h1>
</div>
<form action="{{ route('destinos.paquetes.update', [$destino->id_destino, $paquete->id_paquete]) }}" method="POST">
    @csrf @method('PUT')
    <div class="ea-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-12">
                <label>Nombre del paquete *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $paquete->nombre) }}" class="form-control" required>
            </div>
            <div class="col-12">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $paquete->descripcion) }}</textarea>
            </div>
            <div class="col-md-4">
                <label>Precio (MXN)</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $paquete->precio) }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Tipo de público</label>
                <select name="tipo_publico" id="tipo_publico" class="form-select">
                    <option value="todo" {{ $paquete->tipo_publico == 'todo' ? 'selected' : '' }}>Todo público</option>
                    <option value="especifico" {{ $paquete->tipo_publico == 'especifico' ? 'selected' : '' }}>Rango de edad específico</option>
                </select>
            </div>
            <div class="col-md-4" id="rango-edad" style="{{ $paquete->tipo_publico == 'especifico' ? 'display:block' : 'display:none' }}">
                <label>Edad mínima</label>
                <input type="number" name="edad_minima" value="{{ old('edad_minima', $paquete->edad_minima) }}" class="form-control">
                <label class="mt-2">Edad máxima</label>
                <input type="number" name="edad_maxima" value="{{ old('edad_maxima', $paquete->edad_maxima) }}" class="form-control">
            </div>
        </div>
    </div>

    <div class="ea-card p-4 mb-4">
        <div class="fw-semibold mb-3">Actividades incluidas</div>
        <div id="actividades-container">
            @foreach($actividadesDelPaquete as $index => $actPaq)
            <div class="row g-3 mb-3 actividad-row">
                <div class="col-md-5">
                    <select name="actividades[{{ $index }}][id_actividad]" class="form-select" required>
                        <option value="">Seleccionar actividad</option>
                        @foreach($actividadesDisponibles as $act)
                            <option value="{{ $act->id_actividad }}" {{ $actPaq->id_actividad == $act->id_actividad ? 'selected' : '' }}>{{ $act->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="actividades[{{ $index }}][minimo]" value="{{ $actPaq->minimo_personas }}" class="form-control" placeholder="Mínimo personas">
                </div>
                <div class="col-md-3">
                    <input type="number" name="actividades[{{ $index }}][maximo]" value="{{ $actPaq->maximo_personas }}" class="form-control" placeholder="Máximo personas">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">🗑️</button>
                </div>
            </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-success mt-3" onclick="agregarFila()">+ Agregar otra actividad</button>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('destinos.paquetes.index', $destino->id_destino) }}" class="btn btn-light border">Cancelar</a>
        <button type="submit" class="btn ea-btn-green">Actualizar paquete</button>
    </div>
</form>

<script>
    document.getElementById('tipo_publico').addEventListener('change', function() {
        document.getElementById('rango-edad').style.display = this.value === 'especifico' ? 'block' : 'none';
    });
    let contadorAct = {{ count($actividadesDelPaquete) }};
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