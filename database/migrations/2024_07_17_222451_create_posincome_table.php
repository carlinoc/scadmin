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
        Schema::create('posincome', function (Blueprint $table) {
            $table->id();
            $table->timestamp('incomeDate')->nullable(false);
            $table->decimal('income', $precision=8, $escala=2)->nullable(false);
            $table->string('operationNumber', 50)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('companyPosId')->constrained('companypos')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posincome');
    }
};
