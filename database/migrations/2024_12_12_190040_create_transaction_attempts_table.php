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
        Schema::create('transaction_attempts', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('saved_card_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['manual', 'moto'])->nullable();
            $table->double('amount')->default(0);
            $table->string('currency_code', 10)->nullable();
            $table->boolean('payment_status')->default(false);
            $table->string('paymob_order_id')->nullable();
            $table->string('paymob_transaction_id')->nullable();
            $table->longText('paymob_iframe_token')->nullable();
            $table->longText('paymob_callback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_attempts');
    }
};
