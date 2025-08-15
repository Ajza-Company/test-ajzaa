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
        Schema::create('country_locales', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('locale_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name', 50)->unique();
            $table->string('currency_code', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_locales');
    }
};
