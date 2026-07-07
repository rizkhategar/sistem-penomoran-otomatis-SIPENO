<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@disdukcapil.test'],
            [
                'name' => 'Admin Disdukcapil',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bidang' => 'SEKRETARIATAN',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bidang' => 'PELAYANAN PENDAFTARAN PENDUDUK',
            ]
        );
    }
}
