<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('letter_submissions', 'pengolah')) {
                $table->string('pengolah')->nullable()->after('keperluan');
            }

            if (!Schema::hasColumn('letter_submissions', 'ditujukan_kepada')) {
                $table->string('ditujukan_kepada')->nullable()->after('pengolah');
            }

            if (!Schema::hasColumn('letter_submissions', 'number_format')) {
                $table->string('number_format')->nullable()->after('ditujukan_kepada');
            }
        });

        if (!Schema::hasTable('letter_global_sequences')) {
            Schema::create('letter_global_sequences', function (Blueprint $table) {
                $table->id();
                $table->unsignedTinyInteger('month');
                $table->unsignedSmallInteger('year');
                $table->unsignedInteger('last_number')->default(0);
                $table->timestamps();

                $table->unique(['month', 'year']);
            });
        }

        if (Schema::hasTable('letter_submissions') && Schema::hasTable('letter_global_sequences')) {
            $existingSequences = DB::table('letter_submissions')
                ->selectRaw('MONTH(COALESCE(submission_date, created_at)) as month')
                ->selectRaw('YEAR(COALESCE(submission_date, created_at)) as year')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(letter_number, '/', 1) AS UNSIGNED)) as last_number")
                ->whereNotNull('letter_number')
                ->groupBy('month', 'year')
                ->get();

            foreach ($existingSequences as $sequence) {
                DB::table('letter_global_sequences')->updateOrInsert(
                    ['month' => $sequence->month, 'year' => $sequence->year],
                    [
                        'last_number' => max((int) $sequence->last_number, 0),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_global_sequences');

        Schema::table('letter_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('letter_submissions', 'number_format')) {
                $table->dropColumn('number_format');
            }

            if (Schema::hasColumn('letter_submissions', 'ditujukan_kepada')) {
                $table->dropColumn('ditujukan_kepada');
            }

            if (Schema::hasColumn('letter_submissions', 'pengolah')) {
                $table->dropColumn('pengolah');
            }
        });
    }
};
