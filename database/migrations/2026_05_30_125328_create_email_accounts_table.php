<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            // IMAP Settings (encrypted)
            $table->text('imap_host');
            $table->string('imap_port')->default('993');
            $table->string('imap_encryption')->default('ssl');
            $table->text('imap_username');
            $table->text('imap_password');

            // SMTP Settings (encrypted)
            $table->text('smtp_host');
            $table->string('smtp_port')->default('587');
            $table->string('smtp_encryption')->default('tls');
            $table->text('smtp_username');
            $table->text('smtp_password');

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
