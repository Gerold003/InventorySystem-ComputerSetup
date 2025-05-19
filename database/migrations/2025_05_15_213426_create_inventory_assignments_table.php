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
    Schema::create('inventory_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('inventory_id')->constrained();
        $table->foreignId('department_id')->nullable()->constrained();
        $table->foreignId('employee_id')->nullable()->constrained('users');
        $table->integer('quantity');
        $table->date('assigned_date');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
    // Add to products table migration:
    Schema::table('products', function (Blueprint $table) {
        $table->integer('reorder_threshold')->default(5)->after('price');
        $table->integer('reorder_quantity')->default(20)->after('reorder_threshold');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_assignments');
    }
};
