<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parent_trip_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('child_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->string('stop_name')->nullable(); // optional: notify for specific stop
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parent_trip_subscriptions');
    }
};
