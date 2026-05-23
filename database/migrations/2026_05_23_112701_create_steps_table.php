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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content_html')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->string('media_path')->nullable();
            $table->string('media_type')->nullable();
            $table->string('action_type')->default('acknowledge');
            $table->string('action_label')->nullable();
            $table->string('condition_type')->default('none');
            $table->string('condition_start')->nullable();
            $table->string('condition_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
