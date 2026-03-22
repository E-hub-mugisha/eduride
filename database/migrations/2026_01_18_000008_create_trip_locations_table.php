<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * GPS pings recorded during a live trip.
     * High insert volume — kept intentionally lean.
     * No updated_at column (records are never updated).
     */
    public function up(): void
    {
        Schema::create('trip_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->float('speed')->nullable()->comment('Speed in km/h from device GPS');
            $table->float('heading')->nullable()->comment('Compass bearing 0-360');
            $table->float('accuracy')->nullable()->comment('GPS accuracy radius in metres');
            $table->timestamp('recorded_at')->useCurrent()
                ->comment('Device timestamp of the GPS fix');
            $table->timestamps();
            // Composite index: fetch full path of a trip quickly
            $table->index(['trip_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_locations');
    }
};
