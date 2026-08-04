<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResolutionTypeSeeder extends Seeder
{
    public function run(): void
    {
        ResolutionType::create(['name' => 'Manual Execution']);
        ResolutionType::create(['name' => 'Scaled to provider']);
        ResolutionType::create(['name' => 'Software Installation']);
        ResolutionType::create(['name' => 'Hardware Installation']);
        ResolutionType::create(['name' => 'Recovered without intervention']);
        ResolutionType::create(['name' => 'Duplicated']);
        ResolutionType::create(['name' => 'Does not apply']);
        ResolutionType::create(['name' => 'User permissions management']);
    }
}
