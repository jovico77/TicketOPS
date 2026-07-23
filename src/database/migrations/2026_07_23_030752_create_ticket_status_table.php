<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_status', function (Blueprint $table) {

            $table->id();

            $table->string('name', 50)->unique();

            // Color del Estado (In Progress, Closed, etc.) en formato hexadecimal
            $table->string('color', 7)->nullable();

            // Orden de visualización del estado en la lista de estados
            $table->unsignedTinyInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('ticket_status');
    }
};
