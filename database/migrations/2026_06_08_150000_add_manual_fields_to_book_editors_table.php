<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_editors', function (Blueprint $table) {
            // Make user_id nullable so editors can be added manually
            $table->foreignId('user_id')->nullable()->change();

            // Add manual editor fields (same as book_authors)
            $table->string('name')->nullable()->after('user_id');
            $table->string('name_with_title')->nullable()->after('name');
            $table->string('email')->nullable()->after('name_with_title');
            $table->string('affiliation')->nullable()->after('email');
            $table->string('phone')->nullable()->after('affiliation');
            $table->integer('order')->default(0)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('book_editors', function (Blueprint $table) {
            $table->dropColumn(['name', 'name_with_title', 'email', 'affiliation', 'phone', 'order']);
        });
    }
};
