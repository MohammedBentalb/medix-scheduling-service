<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_scheduling_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('doctorId')->unique();
            $table->smallInteger('bookingWindowDays')->default(60);
            $table->smallInteger('slotDurationMinutes')->default(20);
            $table->boolean('isAvailable')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_scheduling_settings');
    }
};
