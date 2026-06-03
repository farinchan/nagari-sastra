<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop author columns from books (managed via book_authors table)
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['authorString', 'authors']);
        });

        // Add book_id to payment_invoices for book invoices
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->foreignId('book_id')->nullable()->after('id')->constrained('books')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropColumn('book_id');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('authorString')->nullable()->after('slug');
            $table->json('authors')->nullable()->after('authorString');
        });
    }
};
