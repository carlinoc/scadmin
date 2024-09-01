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
        Schema::create('mainbox', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('movementType')->default(0);
            $table->decimal('income', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('expense', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->tinyInteger('state')->default(0);
            $table->tinyInteger('expenseType')->default(0);
            $table->tinyInteger('staffPayType')->default(0);
            $table->tinyInteger('voucherType')->default(0);
            $table->string('voucherNumber', 50)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('payboxId')->constrained('paybox')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('incomeconceptId')->constrained('IncomeConcept')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('serviceId')->constrained('service')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('providerId')->constrained('provider')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('staffId')->constrained('staff')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherpayId')->constrained('otherpay')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mainbox');
    }
};
