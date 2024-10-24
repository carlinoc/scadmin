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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('slogan');
            $table->string('ruc');
            $table->integer('igv');
            $table->string('phone')->nullable();
            $table->string('address', 500)->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
