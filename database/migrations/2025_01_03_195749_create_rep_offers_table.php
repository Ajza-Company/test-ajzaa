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
        Schema::create('rep_offers', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->foreignId('rep_order_id')->constrained()->cascadeOnDelete();
            $table->double('price');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_offers');
    }
};
