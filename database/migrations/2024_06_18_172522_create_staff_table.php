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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('dni', 8)->nullable();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('address', 500)->nullable();
            $table->string('email', 500)->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('areaId')->constrained('Area')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
