<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            
            $table->uuid('id')->primary();

            // Título del ticket
            $table->string('title', 200);

            // Descripción del problema
            $table->text('description')->nullable();

            // Estado del ticket
            $table->foreignId('status_id')->constrained('ticket_status')->restrictOnDelete();

            // Prioridad del ticket
            $table->foreignId('priority_id')->constrained('priorities')->restrictOnDelete();

            // Categoría del ticket
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();

            // Usuario que crea el ticket
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();

            // Usuario asignado
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Fecha de cierre
            $table->timestamp('closed_at')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
