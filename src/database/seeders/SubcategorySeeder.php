<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{

    public function run(): void
    {
        // Buscar las categorías
        $hardware = Category::firstWhere('name', 'Hardware');
        $software = Category::firstWhere('name', 'Software');
        $network = Category::firstWhere('name', 'Network');
        $accounts = Category::firstWhere('name', 'Accounts');
        $infrastructure = Category::firstWhere('name', 'Infrastructure');
        $other = Category::firstWhere('name', 'Other');

        // Definir las subcategorías agrupadas por categoría
        $subcategories = [
            $hardware->id => [
                'Laptop',
                'Desktop',
                'Monitor',
                'Printer',
                'Keyboard',
                'Mouse',
                'Docking Station',
            ],

            $software->id => [
                'Office 365',
                'Outlook',
                'Teams',
                'FortiClient',
                'Adobe Acrobat',
                'SAP',
                'Windows',
            ],

            $network->id => [
                'WiFi',
                'Ethernet',
                'Firewall',
                'VPN',
                'DNS',
                'Switch',
            ],

            $accounts->id => [
                'Password Reset',
                'Unlock Account',
                'New User',
                'Permission Change',
                'MFA',
                'Shared Mailbox',
            ],

            $infrastructure->id => [
                'VMware',
                'Hyper-V',
                'Azure',
                'File Server',
                'Backup',
                'Active Directory',
                'DHCP',
            ],

            $other->id => [
                'General Question',
                'Other',
            ],
        ];

        // Crear las subcategorías en la base de datos
        foreach ($subcategories as $categoryId => $items) {
            foreach ($items as $item) {
                Subcategory::create([
                    'category_id' => $categoryId,
                    'name' => $item,
                ]);
            }
        }
    }
}
