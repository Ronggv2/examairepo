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
        Schema::create('exam_sessions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('exam_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        // Timer control
        $table->integer('duration_minutes'); // e.g. 20
        $table->integer('remaining_seconds')->nullable(); // live countdown

        // State control
        $table->boolean('is_paused')->default(false);
        $table->boolean('is_submitted')->default(false);

        // Timing
        $table->timestamp('started_at')->nullable();
        $table->timestamp('last_activity_at')->nullable();

        $table->timestamp('ends_at')->nullable(); // optional (pre-calculated)

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
