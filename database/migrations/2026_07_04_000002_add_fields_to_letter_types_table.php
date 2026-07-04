<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_types', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('code');
            $table->foreignId('created_by')->nullable()->constrained('users')->after('bidang');
            $table->integer('monthly_quota')->default(5)->after('description');
            $table->integer('daily_insertion')->default(5)->after('monthly_quota');
            $table->boolean('is_active')->default(true)->after('daily_insertion');
        });
    }

    public function down(): void
    {
        Schema::table('letter_types', function (Blueprint $table) {
            $table->dropColumn(['bidang', 'created_by', 'monthly_quota', 'daily_insertion', 'is_active']);
        });
    }
};
