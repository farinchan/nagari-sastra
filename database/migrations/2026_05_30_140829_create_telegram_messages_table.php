<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_bot_id');
            $table->unsignedBigInteger('telegram_chat_id');
            $table->bigInteger('message_id')->nullable(); // Telegram message ID
            $table->string('direction')->default('in'); // in = from user, out = from bot
            $table->longText('text')->nullable();
            $table->string('type')->default('text'); // text, photo, document, sticker, video, voice, location, contact
            $table->string('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('reply_to_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->foreign('telegram_bot_id')->references('id')->on('telegram_bots')->onDelete('cascade');
            $table->foreign('telegram_chat_id')->references('id')->on('telegram_chats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
