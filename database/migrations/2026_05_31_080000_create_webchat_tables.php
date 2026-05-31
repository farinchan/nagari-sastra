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
        Schema::create('webchat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique()->index();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        Schema::create('webchat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webchat_conversation_id')->constrained()->onDelete('cascade');
            $table->enum('sender', ['visitor', 'admin'])->default('visitor');
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('message');
            $table->string('image')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webchat_messages');
        Schema::dropIfExists('webchat_conversations');
    }
};
