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
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('category_type', ['system', 'custom'])->default('system')->after('id');
            $table->foreignId('company_id')->nullable()->after('category_type')->constrained('companies')->cascadeOnDelete();
            
            // Add index for better performance
            $table->index(['category_type', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['category_type', 'company_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['category_type', 'company_id']);
        });
    }
};
