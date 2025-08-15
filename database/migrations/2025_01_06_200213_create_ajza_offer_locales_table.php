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
        Schema::create('ajza_offer_locales', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('ajza_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('locale_id')->constrained()->cascadeOnDelete();
            $table->string('title', 50);
            $table->string('description', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajza_offer_locales');
    }
};
