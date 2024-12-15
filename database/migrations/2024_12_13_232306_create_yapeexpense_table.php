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
        Schema::create('yapeexpense', function (Blueprint $table) {
            $table->id();
            $table->timestamp('expenseDate');
            $table->decimal('expense', $precision=8, $escala=2);
            $table->text('description')->nullable();
            $table->tinyInteger('expenseType')->default(0);
            $table->tinyInteger('voucherType')->default(0);
            $table->string('voucherNumber', 50)->nullable();
            $table->foreignId('expensecategoryId')->constrained('expensecategories')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yapeexpense');
    }
};
