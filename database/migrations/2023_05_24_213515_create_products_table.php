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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 100)->nullable();
            $table->string('image')->nullable();
            $table->decimal('cost', $precision=8, $escala=2)->nullable(false);
            $table->decimal('price', $precision=8, $escala=2)->nullable(false);
            $table->integer('stock')->default(0);    
            $table->integer('minStock')->default(0);
            $table->boolean('useInventory')->default(0);
            $table->enum('inCharge', ['Cocina', 'Barra', 'Otro'])->nullable();
            $table->timestamp('dueDate')->nullable();
            $table->foreignId('categoryId')->constrained('categories')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
