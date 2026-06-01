<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number');
            $table->string('phone_number_id');
            $table->string('waba_id')->nullable();
            $table->text('access_token'); // encrypted in model
            $table->string('verify_token'); // encrypted in model
            $table->boolean('is_active')->default(true);
            $table->boolean('webhook_active')->default(false);
            $table->timestamps();
        });

        Schema::create('whatsapp_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_account_id');
            $table->string('wa_id'); // remote phone number e.g. 6281234567890
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->integer('unread_count')->default(0);
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();
            $table->foreign('whatsapp_account_id')->references('id')->on('whatsapp_accounts')->onDelete('cascade');
            $table->unique(['whatsapp_account_id', 'wa_id']);
        });

        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_chat_id');
            $table->unsignedBigInteger('whatsapp_account_id');
            $table->string('wa_message_id')->nullable()->index();
            $table->enum('direction', ['in', 'out']);
            $table->string('type')->default('text'); // text, image, document, video, audio, sticker, location, template, reaction
            $table->text('body')->nullable();
            $table->string('media_id')->nullable();
            $table->string('media_url')->nullable();
            $table->string('media_mime')->nullable();
            $table->string('file_name')->nullable();
            $table->text('caption')->nullable();
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->foreign('whatsapp_chat_id')->references('id')->on('whatsapp_chats')->onDelete('cascade');
            $table->foreign('whatsapp_account_id')->references('id')->on('whatsapp_accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
        Schema::dropIfExists('whatsapp_chats');
        Schema::dropIfExists('whatsapp_accounts');
    }
};
