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
        Schema::create('car_type_locales', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('car_type_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('locale_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name', 50)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_type_locales');
    }
};
