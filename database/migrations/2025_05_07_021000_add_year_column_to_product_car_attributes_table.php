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
        Schema::table('product_car_attributes', function (Blueprint $table) {
            $table->string('year', 6)->after('car_model_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_car_attributes', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};
