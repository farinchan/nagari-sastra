<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_invoices', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payment_invoices', 'source_type')) {
                $table->string('source_type')->nullable()->default('custom')->after('is_custom');
            }
        });

        // Drop snap_token jika ada (tidak diperlukan)
        if (Schema::hasColumn('payment_invoices', 'snap_token')) {
            Schema::table('payment_invoices', function (Blueprint $table) {
                $table->dropColumn('snap_token');
            });
        }

        // Drop tabel product_order_items dan product_orders (tidak diperlukan lagi)
        Schema::dropIfExists('product_order_items');
        Schema::dropIfExists('product_orders');
    }

    public function down(): void
    {
        Schema::table('payment_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('payment_invoices', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('payment_invoices', 'source_type')) {
                $table->dropColumn('source_type');
            }
        });
    }
};
