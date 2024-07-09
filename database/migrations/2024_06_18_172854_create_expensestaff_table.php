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
        Schema::create('expensestaff', function (Blueprint $table) {
            $table->id();
            $table->dateTime('expenseDate');
            $table->decimal('amount', $precision=8, $escala=2)->nullable(false);
            $table->text('description')->nullable();
            $table->foreignId('staffId')->constrained('Staff')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('payboxId')->constrained('Paybox')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expensestaff');
    }
};
