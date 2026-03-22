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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // Parent user account (role = parent)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Assigned route and boarding stop
            $table->foreignId('route_id')
                ->nullable()
                ->constrained('routes')
                ->nullOnDelete();
            $table->foreignId('stop_id')
                ->nullable()
                ->constrained('stops')
                ->nullOnDelete();

            $table->string('full_name', 150);
            $table->string('student_id', 50)->unique()->nullable()
                ->comment('School registration number');
            $table->string('grade', 20)->nullable();
            $table->string('class_section', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('medical_notes')->nullable()
                ->comment('Allergies or medical conditions relevant to transport');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['route_id', 'stop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
