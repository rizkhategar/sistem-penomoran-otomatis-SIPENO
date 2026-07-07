<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Surat Tugas & SPPD', 'code' => 'ST-SPPD', 'bidang' => 'SEKRETARIATAN', 'description' => 'Surat tugas dan surat perintah perjalanan dinas'],
            ['name' => 'Surat Tugas', 'code' => 'ST', 'bidang' => 'SEKRETARIATAN', 'description' => 'Surat tugas'],
            ['name' => 'Nota Dinas', 'code' => 'ND', 'bidang' => 'SEKRETARIATAN', 'description' => 'Nota dinas'],
            ['name' => 'Naskah Dinas Korespondensi Internal', 'code' => 'NDKI', 'bidang' => 'SEKRETARIATAN', 'description' => 'Naskah dinas korespondensi internal'],
            ['name' => 'Naskah Dinas Korespondensi Eksternal', 'code' => 'NDKE', 'bidang' => 'SEKRETARIATAN', 'description' => 'Naskah dinas korespondensi eksternal'],
            ['name' => 'Naskah Dinas Khusus', 'code' => 'NDK', 'bidang' => 'SEKRETARIATAN', 'description' => 'Naskah dinas khusus'],
            ['name' => 'Naskah Dinas Surat', 'code' => 'NDS', 'bidang' => 'SEKRETARIATAN', 'description' => 'Naskah dinas surat'],
        ];

        foreach ($types as $type) {
            LetterType::updateOrCreate(
                ['name' => $type['name']],
                $type + ['monthly_quota' => 5, 'daily_insertion' => 5, 'is_active' => true]
            );
        }
    }
}
