<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('character', 10)->unique();
            $table->string('pinyin', 255)->nullable();
            $table->text('definition')->nullable();
            $table->string('radical', 50)->nullable();
            $table->text('decomposition')->nullable();
            $table->text('etymology')->nullable();
            $table->string('stroke_count', 10)->nullable();
            $table->timestamps();

            $table->index('character');
            $table->index('radical');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
