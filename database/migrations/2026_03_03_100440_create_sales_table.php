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
        Schema::create('sales', function (Blueprint $table) {
    $table->id();
    $table->string('sale_id')->unique();
    $table->date('date');

    $table->string('product_name')->nullable();
    $table->string('sku')->nullable();

    $table->integer('quantity')->nullable();    
    $table->decimal('amount', 10,2)->nullable(); 

    $table->string('warehouse')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
