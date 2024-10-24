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
        Schema::create('sales_detail', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', $precision=8, $escala=2)->nullable(false);
            $table->integer('quantity')->default(0);
            $table->decimal('total', $precision=8, $escala=2)->nullable(false);
            $table->foreignId('saleId')->constrained('sales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('productId')->constrained('products')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_detail');
    }
};
