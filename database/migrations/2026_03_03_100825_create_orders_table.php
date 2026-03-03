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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_id')->unique();    // ID заказа из API
    $table->dateTime('order_date');          // дата+время заказа (Y-m-d H:i:s)
    $table->string('customer_name')->nullable(); // имя покупателя
    $table->decimal('total_amount', 10,2);  // сумма заказа
    $table->string('status')->nullable();    // статус заказа
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
