<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->datetime('date');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_note')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
