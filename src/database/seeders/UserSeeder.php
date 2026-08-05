<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        User::create([
            'name' => 'Joel Vicente',
            'email' => 'admin@ticketops.local',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole->id,
        ]);
    }
}
