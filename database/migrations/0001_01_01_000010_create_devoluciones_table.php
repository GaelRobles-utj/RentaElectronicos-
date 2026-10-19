<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renta_id')->constrained('rentas')->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('fecha');
            $table->text('observacion')->nullable();
            $table->decimal('cargo_extra', 10, 2)->default(0);
            $table->string('imagen')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devoluciones');
    }
};
