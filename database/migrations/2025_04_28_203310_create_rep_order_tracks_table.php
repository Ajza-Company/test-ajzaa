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
        Schema::create('rep_order_tracks', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->foreignId('rep_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rep_id')->constrained('users')->cascadeOnDelete();
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_order_tracks');
    }
};
