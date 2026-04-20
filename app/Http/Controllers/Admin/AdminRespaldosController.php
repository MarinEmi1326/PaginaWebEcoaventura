<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminRespaldosController extends Controller
{
    public function index()
    {
        // Ruta correcta donde se guardan los backups
        $backupPath = storage_path('app/respaldos');

        // Verificar si el directorio existe
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Obtener todos los archivos .sql
        $archivos = [];
        $files = glob($backupPath . '/*.sql');

        foreach ($files as $file) {
            $archivos[] = [
                'nombre' => basename($file),
                'fecha'  => date('Y-m-d H:i', filemtime($file)),
                'tamaño' => round(filesize($file) / 1024, 2) . ' KB',
                'ruta'   => $file,
            ];
        }

        // Ordenar por fecha (más reciente primero)
        usort($archivos, function ($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        return view('admin.respaldos', compact('archivos'));
    }

    public function generar()
    {
        // Obtener credenciales de la BD desde .env (Hostinger ya las tiene)
        $db       = config('database.connections.mysql.database');
        $user     = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host');

        $fecha    = now()->format('Y-m-d_H-i-s');
        $archivo  = storage_path("app/respaldos/respaldo_{$fecha}.sql");

        // Crear directorio si no existe
        if (!file_exists(storage_path('app/respaldos'))) {
            mkdir(storage_path('app/respaldos'), 0755, true);
        }

        try {
            // Conectar a la base de datos
            $pdo = new \PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Obtener todas las tablas
            $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

            if (empty($tables)) {
                throw new \Exception('No se encontraron tablas en la base de datos.');
            }

            $sql = "-- Backup generado el " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Base de datos: {$db}\n";
            $sql .= "-- Host: {$host}\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                // Estructura de la tabla
                $createTable = $pdo->query("SHOW CREATE TABLE {$table}")->fetch(\PDO::FETCH_ASSOC);
                $sql .= "DROP TABLE IF EXISTS {$table};\n";
                $sql .= $createTable['Create Table'] . ";\n\n";

                // Datos de la tabla
                $rows = $pdo->query("SELECT * FROM {$table}");
                $rowCount = 0;

                while ($row = $rows->fetch(\PDO::FETCH_ASSOC)) {
                    $columns = array_keys($row);
                    $values = array_map(function ($value) use ($pdo) {
                        if ($value === null) return 'NULL';
                        return $pdo->quote($value);
                    }, array_values($row));

                    $sql .= "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                    $rowCount++;
                }

                if ($rowCount > 0) {
                    $sql .= "\n";
                }
            }

            $sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

            // Guardar el archivo
            file_put_contents($archivo, $sql);

            // Verificar que el archivo se creó correctamente
            if (file_exists($archivo) && filesize($archivo) > 0) {
                return redirect()->route('admin.respaldos')
                    ->with('success', 'Respaldo generado correctamente. Tamaño: ' . round(filesize($archivo) / 1024, 2) . ' KB');
            } else {
                throw new \Exception('El archivo de backup se creó vacío o no se pudo crear.');
            }
        } catch (\Exception $e) {
            \Log::error('Error en backup: ' . $e->getMessage());
            return redirect()->route('admin.respaldos')
                ->with('error', 'Error al generar el respaldo: ' . $e->getMessage());
        }
    }

    public function descargar($nombre)
    {
        $ruta = storage_path("app/respaldos/{$nombre}");
        if (!file_exists($ruta)) abort(404);
        return response()->download($ruta);
    }
}
