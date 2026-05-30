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
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->string('kepada')->nullable()->after('invoice_file')->comment('Nama penerima invoice');
            $table->string('kepada_detail')->nullable()->after('kepada')->comment('Detail penerima (afiliasi, alamat, dll)');
            $table->text('keterangan')->nullable()->after('kepada_detail')->comment('Catatan/keterangan invoice');
            $table->unsignedBigInteger('created_by')->nullable()->after('keterangan');
              $table->unsignedBigInteger('confirmed_by')->nullable()->after('created_by')->comment('User ID yang mengkonfirmasi pembayaran');
            $table->string('confirmation_file')->nullable()->after('confirmed_by')->comment('File bukti pembayaran');
            $table->text('confirmation_note')->nullable()->after('confirmation_file')->comment('Catatan konfirmasi');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->dropColumn(['kepada', 'kepada_detail', 'keterangan', 'created_by']);
        });
    }
};
