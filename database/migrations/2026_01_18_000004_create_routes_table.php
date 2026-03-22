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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->enum('type', ['morning', 'afternoon', 'both'])->default('both');
            $table->time('morning_departure')->nullable();
            $table->time('afternoon_departure')->nullable();
            $table->unsignedSmallInteger('estimated_duration_min')->nullable()
                  ->comment('Estimated total trip duration in minutes');
            $table->decimal('total_distance_km', 6, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
