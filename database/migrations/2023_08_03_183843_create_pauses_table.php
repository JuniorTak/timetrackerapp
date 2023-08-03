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
        Schema::create('pauses', function (Blueprint $table) {
            $table->id();
            $table->time('pause_on');
            $table->time('pause_off');
            $table->timestamps();
            $table->foreignId('shift_id')
            ->constrained()
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pauses');
    }
};
