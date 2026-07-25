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

            // Número visible del ticket
            $table->string('ticket_number', 20)->unique();

            // Información principal
            $table->string('title', 255);
            $table->text('description');

            // Usuario creador
            $table->foreignUuid('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Técnico asignado
            $table->foreignUuid('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estado
            $table->foreignId('status_id')
                ->constrained('ticket_status')
                ->restrictOnDelete();

            // Prioridad
            $table->foreignId('priority_id')
                ->constrained()
                ->restrictOnDelete();

            // Categoría
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            // Subcategoría
            $table->foreignId('subcategory_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Resolución escrita por el técnico
            $table->text('resolution')->nullable();

            // Tipo de resolución
            $table->foreignId('resolution_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Fechas
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
