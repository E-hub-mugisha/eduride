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
        Schema::create('transport_notifications', function (Blueprint $table) {
            $table->id();
            // Recipient (parent, admin, or driver)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // The trip this notification relates to (optional)
            $table->foreignId('trip_id')
                ->nullable()
                ->constrained('trips')
                ->nullOnDelete();

            $table->enum('type', [
                'trip_started',       // driver started the trip
                'trip_completed',     // trip ended
                'bus_approaching',    // bus is N minutes from parent stop
                'bus_arrived',        // bus reached the stop
                'trip_delayed',       // trip is running late
                'trip_cancelled',     // trip was cancelled
                'sos',                // driver emergency alert
                'system',             // general system message
            ]);

            $table->string('title', 200);
            $table->text('message');
            $table->json('meta')->nullable()
                ->comment('Extra data e.g. {"eta_minutes": 5, "stop_name": "Kicukiro Gate"}');

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // Push notification tracking
            $table->boolean('push_sent')->default(false);
            $table->timestamp('push_sent_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_notifications');
    }
};
