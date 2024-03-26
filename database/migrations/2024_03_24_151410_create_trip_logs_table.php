<?php

use App\Models\Trip;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_logs', function (Blueprint $table) {
            $table->id();

            $table->enum('status', [
                Trip::STATUS_ASSIGNED, Trip::STATUS_AT_VENDOR, Trip::STATUS_PICKED, Trip::STATUS_DELIVERED
            ]);

            $table->unsignedBigInteger('trip_id');
            $table->timestamps();

            $table->foreign('trip_id')->on('trips')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_logs');
    }
};
