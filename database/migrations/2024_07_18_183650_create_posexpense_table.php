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
        Schema::create('posexpense', function (Blueprint $table) {
            $table->id();
            $table->timestamp('expenseDate')->nullable(false);
            $table->decimal('expense', $precision=8, $escala=2)->nullable(false);
            $table->text('description')->nullable();
            $table->tinyInteger('expenseType', 1)->default(0);
            $table->tinyInteger('staffPayType', 1)->default(0);
            $table->tinyInteger('voucherType', 1)->default(0);
            $table->string('voucherNumber', 50)->nullable();
            $table->foreignId('companyPosId')->constrained('companypos')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('serviceId')->constrained('service')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('providerId')->constrained('provider')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('staffId')->constrained('staff')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('otherPayId')->constrained('otherpay')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posexpense');
    }
};
