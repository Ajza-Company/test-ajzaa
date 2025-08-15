<?php

use App\Enums\DiscountTypeEnum;
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
        Schema::create('store_product_offers', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('store_product_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['fixed', 'percentage']);
            $table->double('discount');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_product_offers');
    }
};
