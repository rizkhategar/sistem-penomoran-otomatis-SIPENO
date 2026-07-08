<?php

namespace Database\Seeders;

use App\Models\LetterType;
use App\Models\MasterBidang;
use App\Models\MasterJenisSurat;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            ['name' => 'PELAYANAN PENDAFTARAN PENDUDUK', 'code' => 'PDP'],
            ['name' => 'PELAYANAN PENCATATAN SIPIL', 'code' => 'PCS'],
            ['name' => 'PIAK', 'code' => 'PIAK'],
            ['name' => 'SEKRETARIATAN', 'code' => 'SET'],
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

        foreach ($bidangs as $bidangData) {
            MasterBidang::updateOrCreate(
                ['name' => $bidangData['name']],
                ['code' => $bidangData['code'], 'is_active' => true]
            );
        }

        foreach ($letterTypes as $typeData) {
            MasterJenisSurat::updateOrCreate(
                ['name' => $typeData['name']],
                [
                    'code' => $typeData['code'],
                    'description' => $typeData['description'],
                    'is_active' => true,
                ]
            );
        }

        foreach (MasterBidang::all() as $bidang) {
            foreach (MasterJenisSurat::all() as $jenisSurat) {
                LetterType::updateOrCreate(
                    [
                        'master_bidang_id' => $bidang->id,
                        'master_jenis_surat_id' => $jenisSurat->id,
                    ],
                    [
                        'name' => $jenisSurat->name,
                        'code' => ($jenisSurat->code ?: 'JS').'-'.($bidang->code ?: 'BDG'),
                        'bidang' => $bidang->name,
                        'description' => $jenisSurat->description,
                        'monthly_quota' => 5,
                        'daily_insertion' => 5,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
