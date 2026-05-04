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
        Schema::create('payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('invoice')->nullable();
            $table->json('items')->nullable()->comment('id, name, qty, detail, amount');
            $table->integer('payment_percent')->nullable();
            $table->integer('payment_amount')->nullable();
            $table->date('payment_due_date')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->string('invoice_file')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('midtrans_transaction_id')->nullable();
            $table->decimal('midtrans_gross_amount_paid', 15, 2)->nullable();
            $table->string('midtrans_payment_method')->nullable();
            $table->timestamp('midtrans_paid_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });

        // Add payment_invoice_id FK to submissions table (created before this migration)
        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('payment_invoice_id')->nullable()->after('charge')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['payment_invoice_id']);
            $table->dropColumn('payment_invoice_id');
        });
        Schema::dropIfExists('payment_invoices');
    }
};
