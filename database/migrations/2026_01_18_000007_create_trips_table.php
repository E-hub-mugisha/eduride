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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')
                ->constrained('routes')
                ->restrictOnDelete();
            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->restrictOnDelete();
            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            $table->enum('type', ['morning', 'afternoon', 'special'])->default('morning');
            $table->enum('status', [
                'scheduled',   // trip is planned but not started
                'in_progress', // driver has started
                'completed',   // trip ended normally
                'cancelled',   // trip was cancelled
                'delayed',     // trip is running late
            ])->default('scheduled');

            $table->timestamp('scheduled_at')->nullable()
                ->comment('Planned departure datetime');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            // Last known GPS ping (denormalised for fast reads)
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->float('current_speed')->nullable()->comment('km/h');
            $table->timestamp('location_updated_at')->nullable();

            $table->unsignedSmallInteger('delay_minutes')->default(0);
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('scheduled_at');
            $table->index(['route_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
