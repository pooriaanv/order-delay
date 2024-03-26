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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('rider_id');
            $table->enum('status', [
                Trip::STATUS_ASSIGNED, Trip::STATUS_AT_VENDOR, Trip::STATUS_PICKED, Trip::STATUS_DELIVERED
            ]);
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('rider_id')->on('users')->references('id');
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
