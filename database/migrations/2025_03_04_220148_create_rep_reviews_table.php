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
        Schema::create('rep_reviews', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rep_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignId('rep_order_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('rating')->comment('1-5 stars');
            $table->text('review_text')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_reviews');
    }
};
