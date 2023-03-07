<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matches_id');
            $table->unsignedBigInteger('club_id');
            $table->smallInteger('points');
            $table->smallInteger('gm');
            $table->smallInteger('gk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches_log');
    }
};
    