<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatery_contact_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('chatery_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatery_contact_group_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->timestamps();

            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatery_contacts');
        Schema::dropIfExists('chatery_contact_groups');
    }
};
