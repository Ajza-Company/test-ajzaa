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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('deletion_status', ['active', 'pending_deletion', 'deleted'])
                ->default('active')
                ->after('is_active')
                ->comment('Account deletion status');
            
            $table->timestamp('deletion_requested_at')
                ->nullable()
                ->after('deletion_status')
                ->comment('When deletion was requested');
            
            $table->text('deletion_reason')
                ->nullable()
                ->after('deletion_requested_at')
                ->comment('Reason for deletion request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['deletion_status', 'deletion_requested_at', 'deletion_reason']);
        });
    }
};
