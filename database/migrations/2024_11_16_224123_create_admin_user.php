<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar si la tabla 'users' está vacía antes de insertar el usuario
        if (DB::table('users')->count() === 0) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@domain.com',
                'password' => Hash::make('password123'), // Encriptar la contraseña
                'email_verified_at' => now(), // Confirmar el email
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar el usuario creado si se revierte la migración
        DB::table('users')->where('email', 'admin@domain.com')->delete();
    }
}
