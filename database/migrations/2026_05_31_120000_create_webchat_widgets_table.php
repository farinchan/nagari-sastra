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
        Schema::create('webchat_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique()->index();
            $table->string('name');
            $table->text('allowed_domains')->nullable();
            $table->string('primary_color')->default('#667eea');
            $table->string('secondary_color')->default('#764ba2');
            $table->text('greeting_message')->nullable();
            $table->string('header_title')->default('Customer Support');
            $table->string('header_subtitle')->default('Online — Siap membantu Anda');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('webchat_conversations', function (Blueprint $table) {
            $table->foreignId('webchat_widget_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webchat_conversations', function (Blueprint $table) {
            $table->dropForeign(['webchat_widget_id']);
            $table->dropColumn('webchat_widget_id');
        });

        Schema::dropIfExists('webchat_widgets');
    }
};
