<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_set_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('auto_change')->default(true);

            $table->string('assign_method')->default('random');
            // random | fixed | manual

            $table->integer('questions_per_user')->default(10);

            $table->string('repeat_policy')->default('none');
            // none | within_exam | across_attempts

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_settings');
    }
};