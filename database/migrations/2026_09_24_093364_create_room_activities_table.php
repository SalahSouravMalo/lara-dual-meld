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
        Schema::create('room_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_player_id')->constrained();
            $table->unsignedInteger('sequence');
            $table->string('action');
            $table->string('card_value');
            $table->timestamps();

            $table->unique(['room_player_id', 'sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_activities');
    }
};
