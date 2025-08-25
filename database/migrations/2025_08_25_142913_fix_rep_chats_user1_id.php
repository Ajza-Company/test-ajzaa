<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix null user1_id in rep_chats by getting it from rep_orders
        DB::statement("
            UPDATE rep_chats rc
            INNER JOIN rep_orders ro ON rc.rep_order_id = ro.id
            SET rc.user1_id = ro.user_id
            WHERE rc.user1_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data, so no rollback needed
        // But if needed, you can set user1_id back to null
    }
};
