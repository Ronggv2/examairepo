<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('subject_prompts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_set_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('subject');

            $table->longText('prompt')->nullable();

            $table->integer('question_count')
                ->default(0);

            $table->string('difficulty')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_prompts');
    }
};