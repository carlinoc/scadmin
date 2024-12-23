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
        Schema::create('expensecategories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->tinyInteger('expenseType')->default(0);
            $table->tinyInteger('isParent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expensecategories');
    }
};
