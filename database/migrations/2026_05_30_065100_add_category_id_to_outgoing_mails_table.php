<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->foreignId('outgoing_mail_category_id')->nullable()->after('nomor_surat')->constrained('outgoing_mail_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->dropForeign(['outgoing_mail_category_id']);
            $table->dropColumn('outgoing_mail_category_id');
        });
    }
};
