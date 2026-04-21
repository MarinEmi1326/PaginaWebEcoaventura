<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }

        .header { background: #0f2a24; color: #fff; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 10px; opacity: .75; }

        .meta { padding: 0 24px 16px; display: flex; gap: 24px; }
        .meta-item { background: #f4f7f4; border-left: 3px solid #2d7a4f; padding: 8px 12px; }
        .meta-item .label { font-size: 9px; color: #666; text-transform: uppercase; margin-bottom: 2px; }
        .meta-item .value { font-size: 11px; font-weight: bold; color: #0f2a24; }

        .filtros { margin: 0 24px 16px; padding: 10px 14px; background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; }
        .filtros .titulo { font-size: 9px; text-transform: uppercase; color: #888; margin-bottom: 6px; }
        .filtros span { font-size: 10px; margin-right: 16px; color: #333; }
        .filtros span b { color: #0f2a24; }

        table { width: 100%; border-collapse: collapse; margin: 0 0 24px; }
        thead tr { background: #0f2a24; color: #fff; }
        thead th { padding: 9px 12px; font-size: 10px; text-align: left; font-weight: 600; }
        tbody tr:nth-child(even) { background: #f4f7f4; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td { padding: 8px 12px; font-size: 10px; border-bottom: 1px solid #e8e8e8; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-green  { background: #d4edda; color: #155724; }
        .badge-blue   { background: #d0e8ff; color: #0c3d6e; }
        .badge-red    { background: #fde8e8; color: #8b1a1a; }
        .badge-gray   { background: #e9e9e9; color: #555; }

        .footer { text-align: center; font-size: 9px; color: #aaa; margin-top: 10px; }
        .tabla-wrap { padding: 0 24px; }
        .total { padding: 0 24px; margin-bottom: 12px; font-size: 10px; color: #555; }
    </style>
</head>
<body>

    {{-- Encabezado --}}
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Ecoaventura — Panel de Administración</p>
    </div>

    {{-- Metadatos --}}
    <div class="meta">
        <div class="meta-item">
            <div class="label">Fecha de generación</div>
            <div class="value">{{ $fechaGeneracion }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Total de registros</div>
            <div class="value">{{ count($data) }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Tipo de reporte</div>
            <div class="value">{{ $tipo === 'por_rol' ? 'Por Rol' : 'Por Estado' }}</div>
        </div>
    </div>

    {{-- Filtros aplicados --}}
    <div class="filtros">
        <div class="titulo">Filtros aplicados</div>
        <span>Rol: <b>{{ !empty($filtros['rol']) ? $filtros['rol'] : 'Todos' }}</b></span>
        <span>Estado: <b>{{ !empty($filtros['estado']) ? ucfirst($filtros['estado']) : 'Todos' }}</b></span>
        <span>Activo: <b>{{ $filtros['activo'] === '1' ? 'Sí' : ($filtros['activo'] === '0' ? 'No' : 'Todos') }}</b></span>
    </div>

    {{-- Tabla --}}
    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Activo</th>
                    <th>Fecha solicitud</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $u)
                    @php
                        $rolLabel = match($u->rol) {
                            'admin_destinos' => 'Admin. Destinos',
                            'gestor_rutas'   => 'Gestor Rutas',
                            'turista'        => 'Turista',
                            default          => $u->rol
                        };
                        $estadoClass = match($u->estado) {
                            'aprobado'  => 'badge-green',
                            'pendiente' => 'badge-blue',
                            'rechazado' => 'badge-red',
                            default     => 'badge-gray'
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $u->nombre_completo }}</td>
                        <td>{{ $u->correo }}</td>
                        <td>{{ $rolLabel }}</td>
                        <td><span class="badge {{ $estadoClass }}">{{ ucfirst($u->estado) }}</span></td>
                        <td><span class="badge {{ $u->activo ? 'badge-green' : 'badge-red' }}">{{ $u->activo ? 'Sí' : 'No' }}</span></td>
                        <td>{{ $u->fecha_solicitud ? \Carbon\Carbon::parse($u->fecha_solicitud)->format('d/m/Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:16px; color:#888;">
                            No hay registros con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Reporte generado automáticamente por el sistema Ecoaventura &mdash; {{ $fechaGeneracion }}
    </div>

</body>
</html>