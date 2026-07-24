<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Hardware',
            'description' => 'Incidencias relacionadas con equipos físicos.',
        ]);

        Category::create([
            'name' => 'Software',
            'description' => 'Errores o instalación de aplicaciones.',
        ]);

        Category::create([
            'name' => 'Network',
            'description' => 'Problemas de conectividad y comunicaciones.',
        ]);

        Category::create([
            'name' => 'Accounts',
            'description' => 'Usuarios, permisos y autenticación.',
        ]);

        Category::create([
            'name' => 'Infrastructure',
            'description' => 'Servidores, virtualización y cloud.',
        ]);

        Category::create([
            'name' => 'Other',
            'description' => 'Incidencias que no pertenecen a otra categoría.',
        ]);
    }
}