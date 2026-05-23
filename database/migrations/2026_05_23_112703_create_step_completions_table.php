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
        Schema::create('step_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('step_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->json('action_data')->nullable();
            $table->timestamps();

            $table->unique(['guide_session_id', 'step_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('step_completions');
    }
};
