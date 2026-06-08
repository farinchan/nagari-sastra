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
        Schema::table('books', function (Blueprint $table) {
            $table->string('qrcbn')->nullable()->after('isbn');
            $table->string('isbn_file')->nullable()->after('qrcbn');
            $table->string('qrcbn_file')->nullable()->after('isbn_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['qrcbn', 'isbn_file', 'qrcbn_file']);
        });
    }
};
