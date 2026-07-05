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
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('coach_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('completed_at');
            $table->string('program_name', 100)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
