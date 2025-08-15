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
        Schema::table('rep_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('rep_user_id')->after('rep_order_id')->nullable();
            $table->foreign('rep_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rep_offers', function (Blueprint $table) {
            $table->dropForeign(['rep_user_id']);
            $table->dropColumn('rep_user_id');
        });
    }
};