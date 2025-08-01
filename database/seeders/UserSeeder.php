<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB; // Use DB facade for direct insertion
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([ // Using insertOrIgnore to prevent duplicates on re-seed
            [
                'username' => 'akri-dev',
                'email' => 'alecjoseph.dev@gmail.com',
                'password' => Hash::make('akriDev1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => 1
            ],
            [
                'username' => 'pastor',
                'email' => 'pastor@mail.com',
                'password' => Hash::make('pastor1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => 2
            ],
            [
                'username' => 'armor-bearer',
                'email' => 'armor-bearer@mail.com',
                'password' => Hash::make('armor1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => 3
            ],
            [
                'username' => 'new-member',
                'email' => 'new-member@mail.com',
                'password' => Hash::make('newMember1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => 4
            ],
        ]);
    }
}
