<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_submissions', function (Blueprint $table) {
            $table->boolean('is_sk')->default(false)->after('letter_number');
            $table->date('submission_date')->nullable()->after('is_sk');
        });
    }

    public function down(): void
    {
        Schema::table('letter_submissions', function (Blueprint $table) {
            $table->dropColumn(['is_sk', 'submission_date']);
        });
    }
};
