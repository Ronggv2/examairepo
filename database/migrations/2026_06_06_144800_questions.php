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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('question_set_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('subject_prompt_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->text('question');

        $table->enum('type', [
            'mcq',
            'true_false',
            'short_answer',
            'essay',
            'fill_blank'
        ])->default('mcq');

        $table->enum('difficulty', [
            'easy',
            'medium',
            'hard'
        ])->default('medium');

        $table->text('correct_answer')->nullable();

        $table->text('explanation')->nullable();

        $table->integer('marks')
            ->default(1);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
