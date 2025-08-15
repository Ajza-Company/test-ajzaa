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
        Schema::create('support_chat_messages', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->foreignId('support_chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->string('message_type', 20)->default('text');
            $table->string('attachment', 150)->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_from_support')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_chat_messages');
    }
};