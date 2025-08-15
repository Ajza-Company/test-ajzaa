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
        Schema::create('variant_category_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_category_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('locale_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_category_locales');
    }
};
