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
        Schema::create('saleshistory', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->decimal('lasttotal', $precision=8, $escala=2)->nullable(false);
            $table->decimal('newtotal', $precision=8, $escala=2)->nullable(false);
            $table->integer('discount')->default(0);
            $table->integer('newdiscount')->default(0);
            $table->integer('quantity')->default(0);
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('saleId')->constrained('Sales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('productId')->constrained('Products')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleshistory');
    }
};
