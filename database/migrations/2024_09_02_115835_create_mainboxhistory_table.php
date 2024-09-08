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
        Schema::create('mainboxhistory', function (Blueprint $table) {
            $table->id();
            $table->string('action')->nullable();
            $table->decimal('lastincome', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('newincome', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('lastexpense', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->decimal('newexpense', $precision=8, $escala=2)->default(0)->nullable(false);
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('mainboxId')->constrained('mainbox')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mainboxhistory');
    }
};
