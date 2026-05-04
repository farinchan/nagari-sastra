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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('authorString')->nullable();
            $table->json('authors')->nullable();
            $table->string('publisher')->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('edition')->nullable();
            $table->year('publish_year')->nullable();
            $table->integer('pages')->nullable();
            $table->string('size')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->enum('language', ['en', 'id', 'jp'])->default('id');
            $table->mediumText('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('preview_file')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['title', 'slug', 'status', 'book_category_id', 'isbn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
