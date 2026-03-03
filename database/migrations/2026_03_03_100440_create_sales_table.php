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
    $table->id();                    // внутренний PK Laravel
    $table->string('sale_id')->unique(); // ID продажи из API
    $table->date('date');            // дата продажи (Y-m-d)
    $table->string('product_name');  // название продукта
    $table->integer('quantity');     // количество
    $table->decimal('amount', 10,2); // сумма
    $table->string('warehouse')->nullable(); // склад, если API отдаёт
    $table->timestamps();            // created_at, updated_at
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
