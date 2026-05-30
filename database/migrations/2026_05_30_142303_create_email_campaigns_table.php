<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_account_id');
            $table->unsignedBigInteger('email_group_id');
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->string('status')->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('email_account_id')->references('id')->on('email_accounts')->onDelete('cascade');
            $table->foreign('email_group_id')->references('id')->on('email_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
