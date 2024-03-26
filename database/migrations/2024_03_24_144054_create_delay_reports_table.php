<?php

use App\Models\DelayReport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delay_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->enum('status', [DelayReport::STATUS_CHECKED, DelayReport::STATUS_PENDING]);
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('agent_id')->on('users')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delay_reports');
    }
};
