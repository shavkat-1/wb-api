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
        Schema::create('stocks', function (Blueprint $table) {
    $table->id();
    $table->string('stock_id')->unique();  // ID записи склада из API
    $table->date('date');                  // дата выгрузки
    $table->string('warehouse');           // склад
    $table->string('product_name');        // продукт
    $table->integer('quantity');           // количество на складе
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
