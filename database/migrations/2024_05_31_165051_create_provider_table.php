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
        Schema::create('provider', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('contactName')->nullable();
            $table->string('contactPhone')->nullable();
            $table->char('paymentMethod', 128);
            $table->string('accountNumber')->nullable();
            $table->string('yapeNumber')->nullable();
            $table->string('plinNumber')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider');
    }
};
