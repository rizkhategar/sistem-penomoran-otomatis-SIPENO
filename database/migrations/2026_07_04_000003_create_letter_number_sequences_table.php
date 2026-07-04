<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_number_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_type_id')->constrained('letter_types');
            $table->string('bidang');
            $table->string('month'); // numeric: 1-12
            $table->string('year');
            $table->integer('last_number')->default(0);
            $table->timestamps();

            $table->unique(['letter_type_id', 'bidang', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_number_sequences');
    }
};
