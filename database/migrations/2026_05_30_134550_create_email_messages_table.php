<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_account_id');
            $table->unsignedBigInteger('uid')->index();
            $table->string('message_id')->nullable();
            $table->string('folder')->default('INBOX')->index();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->text('to_email')->nullable(); // JSON
            $table->text('cc_email')->nullable(); // JSON
            $table->string('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('has_attachment')->default(false);
            $table->timestamp('email_date')->nullable()->index();
            $table->timestamps();

            $table->foreign('email_account_id')->references('id')->on('email_accounts')->onDelete('cascade');
            $table->unique(['email_account_id', 'uid', 'folder']);
        });

        // Add last_synced_uid to email_accounts
        Schema::table('email_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('last_synced_uid')->default(0)->after('is_default');
            $table->timestamp('last_synced_at')->nullable()->after('last_synced_uid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_messages');

        Schema::table('email_accounts', function (Blueprint $table) {
            $table->dropColumn(['last_synced_uid', 'last_synced_at']);
        });
    }
};
