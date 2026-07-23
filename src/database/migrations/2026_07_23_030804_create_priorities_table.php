<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('priorities', function (Blueprint $table) {
            
            $table->id();
            
            // El nombre de la prioridad (Low, Medium, High, Urgent, etc.)
            $table->string('name', 50)->unique();
            
            // El color asociado a la prioridad en formato hexadecimal (por ejemplo, #FF0000 para rojo)
            $table->string('color', 7)->nullable();

            // Nivel de prioridad (1 = baja, 2 = media, 3 = alta, etc.)
            $table->unsignedTinyInteger('level')->default(1); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
