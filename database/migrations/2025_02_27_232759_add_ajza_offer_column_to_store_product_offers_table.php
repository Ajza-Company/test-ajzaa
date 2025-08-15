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
        Schema::table('store_product_offers', function (Blueprint $table) {
            $table->boolean('ajza_offer')->after('expires_at')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_product_offers', function (Blueprint $table) {
            $table->dropColumn('ajza_offer');
        });
    }
};
