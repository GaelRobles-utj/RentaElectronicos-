<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renta_id')->constrained('rentas')->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('fecha');
            $table->string('metodo', 50);
            $table->decimal('monto', 10, 2);
            $table->string('referencia', 120)->nullable();
            $table->string('comprobante')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
