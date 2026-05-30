<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_mails', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->string('tujuan');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->enum('klasifikasi', ['biasa', 'penting', 'rahasia', 'sangat_rahasia'])->default('biasa');
            $table->string('file_surat')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_mails');
    }
};
