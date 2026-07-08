<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('master_bidangs')) {
            Schema::create('master_bidangs', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code', 20)->nullable()->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('master_jenis_surats')) {
            Schema::create('master_jenis_surats', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code', 50)->nullable()->unique();
                $table->string('description', 500)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('letter_types')) {
            Schema::table('letter_types', function (Blueprint $table) {
                if (!Schema::hasColumn('letter_types', 'master_bidang_id')) {
                    $table->foreignId('master_bidang_id')->nullable()->after('id')->constrained('master_bidangs')->nullOnDelete();
                }

                if (!Schema::hasColumn('letter_types', 'master_jenis_surat_id')) {
                    $table->foreignId('master_jenis_surat_id')->nullable()->after('master_bidang_id')->constrained('master_jenis_surats')->nullOnDelete();
                }
            });
        }

        if (!Schema::hasTable('letter_daily_sequences')) {
            Schema::create('letter_daily_sequences', function (Blueprint $table) {
                $table->id();
                $table->date('sequence_date')->unique();
                $table->unsignedInteger('last_regular_number')->default(0);
                $table->unsignedTinyInteger('insertion_used')->default(0);
                $table->timestamps();
            });
        }

        $this->seedMasterData();
        $this->syncLetterTypesToMasterData();
        $this->seedDailySequencesFromExistingLetters();
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_daily_sequences');

        if (Schema::hasTable('letter_types')) {
            Schema::table('letter_types', function (Blueprint $table) {
                if (Schema::hasColumn('letter_types', 'master_jenis_surat_id')) {
                    $table->dropConstrainedForeignId('master_jenis_surat_id');
                }

                if (Schema::hasColumn('letter_types', 'master_bidang_id')) {
                    $table->dropConstrainedForeignId('master_bidang_id');
                }
            });
        }

        Schema::dropIfExists('master_jenis_surats');
        Schema::dropIfExists('master_bidangs');
    }

    private function seedMasterData(): void
    {
        $now = now();
        $bidangs = [
            ['name' => 'PELAYANAN PENDAFTARAN PENDUDUK', 'code' => 'PDP'],
            ['name' => 'PELAYANAN PENCATATAN SIPIL', 'code' => 'PCS'],
            ['name' => 'PIAK', 'code' => 'PIAK'],
            ['name' => 'SEKRETARIATAN', 'code' => 'SET'],
        ];

        foreach ($bidangs as $bidang) {
            DB::table('master_bidangs')->updateOrInsert(
                ['name' => $bidang['name']],
                ['code' => $bidang['code'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $jenisSurats = [
            ['name' => 'Surat Tugas & SPPD', 'code' => 'ST-SPPD', 'description' => 'Surat tugas dan surat perintah perjalanan dinas'],
            ['name' => 'Surat Tugas', 'code' => 'ST', 'description' => 'Surat tugas'],
            ['name' => 'Nota Dinas', 'code' => 'ND', 'description' => 'Nota dinas'],
            ['name' => 'Naskah Dinas Korespondensi Internal', 'code' => 'NDKI', 'description' => 'Naskah dinas korespondensi internal'],
            ['name' => 'Naskah Dinas Korespondensi Eksternal', 'code' => 'NDKE', 'description' => 'Naskah dinas korespondensi eksternal'],
            ['name' => 'Naskah Dinas Khusus', 'code' => 'NDK', 'description' => 'Naskah dinas khusus'],
            ['name' => 'Naskah Dinas Surat', 'code' => 'NDS', 'description' => 'Naskah dinas surat'],
        ];

        foreach ($jenisSurats as $jenisSurat) {
            DB::table('master_jenis_surats')->updateOrInsert(
                ['name' => $jenisSurat['name']],
                [
                    'code' => $jenisSurat['code'],
                    'description' => $jenisSurat['description'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function syncLetterTypesToMasterData(): void
    {
        if (!Schema::hasTable('letter_types')) {
            return;
        }

        $letterTypes = DB::table('letter_types')->get();

        foreach ($letterTypes as $type) {
            $bidangId = null;
            $jenisSuratId = null;

            if (!empty($type->bidang)) {
                $bidangId = DB::table('master_bidangs')->where('name', $type->bidang)->value('id');
            }

            if (!empty($type->name)) {
                $jenisSuratId = DB::table('master_jenis_surats')->where('name', $type->name)->value('id');
            }

            DB::table('letter_types')
                ->where('id', $type->id)
                ->update([
                    'master_bidang_id' => $bidangId,
                    'master_jenis_surat_id' => $jenisSuratId,
                    'updated_at' => now(),
                ]);
        }
    }

    private function seedDailySequencesFromExistingLetters(): void
    {
        if (!Schema::hasTable('letter_submissions') || !Schema::hasTable('letter_daily_sequences')) {
            return;
        }

        $rows = DB::table('letter_submissions')
            ->selectRaw('DATE(COALESCE(submission_date, created_at)) as sequence_date')
            ->selectRaw("MAX(CASE WHEN COALESCE(is_sk, 0) = 0 THEN CAST(SUBSTRING_INDEX(letter_number, '/', 1) AS UNSIGNED) ELSE 0 END) as last_regular_number")
            ->selectRaw('SUM(CASE WHEN COALESCE(is_sk, 0) = 1 THEN 1 ELSE 0 END) as insertion_used')
            ->whereNotNull('letter_number')
            ->groupBy('sequence_date')
            ->get();

        foreach ($rows as $row) {
            DB::table('letter_daily_sequences')->updateOrInsert(
                ['sequence_date' => $row->sequence_date],
                [
                    'last_regular_number' => max((int) $row->last_regular_number, 0),
                    'insertion_used' => min((int) $row->insertion_used, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
};
