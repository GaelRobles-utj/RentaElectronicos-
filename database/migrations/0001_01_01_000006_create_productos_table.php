<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained('tipos')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('marca_id')->constrained('marcas')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_dia', 10, 2);
            $table->unsignedInteger('existencia')->default(0);
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('imagen1')->nullable();
            $table->string('imagen2')->nullable();
            $table->string('imagen3')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
