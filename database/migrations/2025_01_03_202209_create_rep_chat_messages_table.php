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
        Schema::create('rep_chat_messages', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->foreignId('rep_chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rep_offer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->string('message_type', 20)->default('text');
            $table->string('attachment', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_chat_messages');
    }
};
