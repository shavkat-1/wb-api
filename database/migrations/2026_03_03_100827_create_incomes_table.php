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
         Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('income_id')->unique();        // ID дохода из API
            $table->date('date')->nullable();            // дата дохода (Y-m-d)
            $table->decimal('amount', 10,2)->default(0); // сумма, дефолт 0
            $table->string('source')->nullable();        // источник дохода
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
