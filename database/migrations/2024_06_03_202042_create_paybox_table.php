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
        Schema::create('paybox', function (Blueprint $table) {
            $table->id();
            $table->dateTime('startDate');
            $table->dateTime('closingDate');
            $table->decimal('cashBalance', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('income', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('expenses', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('cashSales', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('cardSales', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('missingBalance', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('leftoverBalance', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('finalBalance', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->tinyInteger('state', 1)->default(0);
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paybox');
    }
};
