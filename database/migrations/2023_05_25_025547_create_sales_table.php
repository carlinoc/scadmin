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
            $table->decimal('subtotal', $precision=8, $escala=2)->nullable(false);
            $table->integer('discount')->default(0);
            $table->decimal('total', $precision=8, $escala=2)->nullable(false);
            $table->tinyInteger('withCash', 4)->default(0);
            $table->integer('status')->default(0);
            $table->tinyInteger('voucherType', 4)->default(0);
            $table->decimal('tips', $precision=8, $escala=2)->nullable(false);
            $table->tinyInteger('tipstype', 4)->default(0);
            $table->foreignId('tableId')->constrained('tables')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('clientId')->constrained('clients')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('payboxId')->constrained('paybox')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('companyPosId')->constrained('company')->onUpdate('cascade')->onDelete('restrict');
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
