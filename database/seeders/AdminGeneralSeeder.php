<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar revisiones de llaves foráneas para evitar errores al limpiar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Opcional: Limpiar registros previos para evitar duplicados si se corre varias veces
        // Usuario::where('correo', 'admin@ecoaventura.com')->delete();

        // 1. Crear el registro en la tabla 'usuario'
        $usuario = Usuario::create([
            'correo'            => 'admin@ecoaventura.com',
            'password'          => Hash::make('admin123'),
            'activo'            => true,
            'estado'            => 'aprobado',
            'correo_verificado' => 1,
            'fecha_solicitud'   => now(),
            'fecha_respuesta'   => now(),
        ]);

        // 2. Crear el registro en la tabla 'persona' vinculado al usuario
        $persona = Persona::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre'     => 'Kevin',
            'apellidos'  => 'Aguilar Gordillo',
            'telefono'   => '9191784877',
        ]);

        // 3. Buscar el ID del rol 'admin_general'
        $rolAdmin = Rol::where('descripcion', 'admin_general')->first();

        if ($rolAdmin) {
            // 4. Asignar el rol en la tabla intermedia 'persona_rol'
            // Usamos attach() porque el modelo Persona tiene la relación roles() definida
            $persona->roles()->attach($rolAdmin->id_rol);
            
            $this->command->info('¡Usuario Administrador General creado con éxito!');
        } else {
            $this->command->error('Error: El rol "admin_general" no existe en la tabla "rol".');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}