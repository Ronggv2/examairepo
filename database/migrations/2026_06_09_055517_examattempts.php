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
        Schema::create('exam_attempts', function (Blueprint $table) {
           $table->id();

            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            $table->integer('score')->default(0);
            $table->integer('total_marks')->default(0);

            $table->integer('correct_count')->default(0);
            $table->integer('incorrect_count')->default(0);
            $table->integer('unanswered_count')->default(0);

            $table->decimal('percentage', 5, 2)->default(0);

            $table->integer('exam_duration_seconds')->default(0);
            $table->integer('time_used_seconds')->default(0);
            $table->integer('time_left_seconds')->default(0);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
