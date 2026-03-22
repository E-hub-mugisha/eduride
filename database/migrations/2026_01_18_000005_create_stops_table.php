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
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')
                ->constrained('routes')
                ->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('landmark')->nullable()
                ->comment('Nearby landmark description for easy identification');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedSmallInteger('order')
                ->comment('Stop order along the route, starting from 1');
            $table->unsignedSmallInteger('arrival_offset_min')->default(0)
                ->comment('Minutes after departure to reach this stop');
            $table->unsignedSmallInteger('dwell_time_sec')->default(30)
                ->comment('Seconds bus waits at this stop');
            $table->timestamps();

            $table->unique(['route_id', 'order']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
