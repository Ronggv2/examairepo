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
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('question_set_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('topic');

            $table->string('subject')
                ->nullable();

            $table->integer('question_count')
                ->default(10);

            $table->string('difficulty')
                ->nullable();

            $table->longText('prompt');

            $table->longText('response')
                ->nullable();

            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};
