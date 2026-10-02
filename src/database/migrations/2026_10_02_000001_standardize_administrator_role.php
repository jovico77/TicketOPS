<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $legacyRoleId = DB::table('roles')->where('name', 'Admin')->value('id');

        if ($legacyRoleId === null) {
            return;
        }

        $administratorRoleId = DB::table('roles')->where('name', 'Administrator')->value('id');

        if ($administratorRoleId === null) {
            DB::table('roles')
                ->where('id', $legacyRoleId)
                ->update([
                    'name' => 'Administrator',
                    'updated_at' => now(),
                ]);

            return;
        }

        DB::table('users')
            ->where('role_id', $legacyRoleId)
            ->update([
                'role_id' => $administratorRoleId,
                'updated_at' => now(),
            ]);

        DB::table('roles')->where('id', $legacyRoleId)->delete();
    }

    public function down(): void
    {
        // The merged role assignments cannot be reliably separated during rollback.
    }
};
