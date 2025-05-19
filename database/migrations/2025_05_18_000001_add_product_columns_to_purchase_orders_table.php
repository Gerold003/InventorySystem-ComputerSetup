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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('supplier_id')->constrained()->onDelete('set null');
            $table->integer('quantity')->default(0)->after('product_id');
            // Make supplier_id nullable since we might not need it for direct product orders
            $table->foreignId('supplier_id')->nullable()->change();
            // Make po_number nullable since it might be auto-generated later
            $table->string('po_number')->nullable()->change();
            // Make order_date default to current date
            $table->date('order_date')->default(now())->change();
            // Make expected_delivery_date nullable
            $table->date('expected_delivery_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity']);
            $table->foreignId('supplier_id')->nullable(false)->change();
            $table->string('po_number')->nullable(false)->change();
            $table->date('order_date')->default(null)->change();
            $table->date('expected_delivery_date')->nullable(false)->change();
        });
    }
}; 