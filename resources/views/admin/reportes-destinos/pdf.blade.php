<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .fecha {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .filtros {
            font-size: 11px;
            margin-bottom: 15px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <div class="fecha">Generado: {{ $fechaGeneracion }}</div>

    <div class="filtros">
        <strong>Filtros aplicados:</strong><br>
        @if(!empty($filtros['estado']))
            Estado: {{ ucfirst($filtros['estado']) }}<br>
        @endif
        @if(!empty($filtros['fecha_desde']) || !empty($filtros['fecha_hasta']))
            Rango de fechas: {{ $filtros['fecha_desde'] ?? 'inicio' }} al {{ $filtros['fecha_hasta'] ?? 'hoy' }}<br>
        @endif
        @if(!empty($filtros['categoria_id']))
            Categoría: {{ $filtros['categoria_id'] }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha creación</th>
                @if(isset($data[0]->categorias))
                    <th>Categorías</th>
                @endif
                @if(isset($data[0]->total_paquetes))
                    <th>Total paquetes</th>
                    <th>Paquetes</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item->destino_nombre }}</td>
                    <td>{{ $item->activo === 'activo' ? 'Activo' : 'Inactivo' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y') }}</td>
                    @if(isset($item->categorias))
                        <td>{{ $item->categorias ?: 'Sin categoría' }}</td>
                    @endif
                    @if(isset($item->total_paquetes))
                        <td>{{ $item->total_paquetes }}</td>
                        <td>{{ $item->paquetes_nombres ?? '' }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="10">No hay datos para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Reporte generado desde el sistema Ecoaventura</div>
</body>
</html>