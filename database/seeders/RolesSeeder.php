<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Use DB facade for direct insertion

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([ // Using insertOrIgnore to prevent duplicates on re-seed
            ['role_name' => 'system-admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'ministry-leader', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'ministry-staff', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'church-member', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
