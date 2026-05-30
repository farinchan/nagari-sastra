<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_group_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->boolean('is_subscribed')->default(true);
            $table->timestamps();
            $table->foreign('email_group_id')->references('id')->on('email_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_contacts');
    }
};
