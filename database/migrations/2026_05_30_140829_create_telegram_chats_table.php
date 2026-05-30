<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_bot_id');
            $table->bigInteger('chat_id')->index(); // Telegram chat ID (can be negative for groups)
            $table->string('chat_type')->default('private'); // private, group, supergroup, channel
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('title')->nullable(); // for groups
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->foreign('telegram_bot_id')->references('id')->on('telegram_bots')->onDelete('cascade');
            $table->unique(['telegram_bot_id', 'chat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_chats');
    }
};
