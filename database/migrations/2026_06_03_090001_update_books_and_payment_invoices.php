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
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('authorString')->nullable()->after('slug');
            $table->json('authors')->nullable()->after('authorString');
        });
    }
};
