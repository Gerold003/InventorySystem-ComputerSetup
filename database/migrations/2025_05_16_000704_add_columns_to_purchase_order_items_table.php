<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('purchase_order_items', function (Blueprint $table) {
        // Add missing columns
        $table->foreignId('product_id')->constrained()->after('purchase_order_id');
        $table->integer('quantity')->after('product_id');
        $table->decimal('unit_price', 10, 2)->after('quantity');
    });

    Schema::table('purchase_orders', function (Blueprint $table) {
        // Add approval columns
        $table->foreignId('approved_by')->nullable()->constrained('users')->after('user_id');
        $table->timestamp('approved_at')->nullable()->after('approved_by');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            //
        });
    }
};
