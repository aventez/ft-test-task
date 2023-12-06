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
        Schema::create('duels', function (Blueprint $table) {
            $table->id();
            $table->integer('round')->unsigned()->default(1);
            $table->string('status', 50);
            $table->integer('won')->nullable();
            $table->foreignId('first_user_id')->constrained('users');
            $table->foreignId('first_user_selected_card_id')->nullable()->constrained('cards');
            $table->integer('first_user_points')->default(0);
            $table->foreignId('second_user_id')->constrained('users');
            $table->foreignId('second_user_selected_card_id')->nullable()->constrained('cards');
            $table->integer('second_user_points')->default(0);
            $table->json('already_used_cards')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duels');
    }
};
