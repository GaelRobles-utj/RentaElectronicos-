<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 120);
            $table->string('correo', 150)->unique();
            $table->string('usuario', 80)->unique();
            $table->string('contrasena');
            $table->string('imagen')->nullable();
            $table->enum('rol', ['superadministrador', 'administrador', 'empleado'])->default('empleado');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};
