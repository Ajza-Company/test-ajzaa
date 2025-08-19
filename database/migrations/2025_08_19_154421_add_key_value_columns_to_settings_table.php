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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'key')) {
                $table->string('key')->after('id');
            }
            if (!Schema::hasColumn('settings', 'value')) {
                $table->json('value')->after('key');
            }
            if (!Schema::hasColumn('settings', 'type')) {
                $table->string('type')->default('string')->after('value');
            }
            if (!Schema::hasColumn('settings', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['key', 'value', 'type', 'is_active']);
        });
    }
};
