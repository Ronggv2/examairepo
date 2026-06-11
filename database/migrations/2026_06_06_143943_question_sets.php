<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('question_sets', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->string('title');
        $table->text('description')->nullable();

        $table->string('subject')->nullable();

        $table->enum('difficulty', [
            'easy',
            'medium',
            'hard',
            'mixed'
        ])->default('mixed');

        $table->enum('status', [
            'draft',
            'published',
            'archived'
        ])->default('draft');

        $table->boolean('is_ai_generated')->default(false);

        $table->integer('total_questions')->default(0);

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_sets');
    }
};