<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            'PELAYANAN PENDAFTARAN PENDUDUK' => 'PDP',
            'PELAYANAN PENCATATAN SIPIL' => 'PCS',
            'PIAK' => 'PIAK',
            'SEKRETARIATAN' => 'SET',
        ];

        $letterTypes = [
            ['name' => 'Surat Tugas & SPPD', 'code' => 'ST-SPPD', 'description' => 'Surat tugas dan surat perintah perjalanan dinas'],
            ['name' => 'Surat Tugas', 'code' => 'ST', 'description' => 'Surat tugas'],
            ['name' => 'Nota Dinas', 'code' => 'ND', 'description' => 'Nota dinas'],
            ['name' => 'Naskah Dinas Korespondensi Internal', 'code' => 'NDKI', 'description' => 'Naskah dinas korespondensi internal'],
            ['name' => 'Naskah Dinas Korespondensi Eksternal', 'code' => 'NDKE', 'description' => 'Naskah dinas korespondensi eksternal'],
            ['name' => 'Naskah Dinas Khusus', 'code' => 'NDK', 'description' => 'Naskah dinas khusus'],
            ['name' => 'Naskah Dinas Surat', 'code' => 'NDS', 'description' => 'Naskah dinas surat'],
        ];

        foreach ($bidangs as $bidang => $suffix) {
            foreach ($letterTypes as $type) {
                LetterType::updateOrCreate(
                    ['name' => $type['name'], 'bidang' => $bidang],
                    [
                        'code' => $type['code'].'-'.$suffix,
                        'description' => $type['description'],
                        'monthly_quota' => 5,
                        'daily_insertion' => 5,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
