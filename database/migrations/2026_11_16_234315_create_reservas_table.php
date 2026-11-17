<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            // Asegura la relación con la tabla espacios y borrado en cascada
            $table->foreignId('espacio_id')->constrained('espacios')->onDelete('cascade');
            $table->date('fecha');
            $table->string('hora_entrada');
            $table->string('hora_salida');
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};