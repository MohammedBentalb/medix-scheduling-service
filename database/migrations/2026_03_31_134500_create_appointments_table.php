<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patientId');
            $table->string('patientName', 100);
            $table->uuid('doctorId');
            $table->string('doctorName', 100);
            $table->uuid('bookedBy');
            $table->date('appointmentDate');
            $table->time('startTime');
            $table->time('endTime');
            $table->string('type', 20)->default('INPERSON');
            $table->string('status', 20)->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['doctorId', 'appointmentDate', 'startTime']);
            $table->index(['doctorId', 'appointmentDate']);
            $table->index(['patientId', 'appointmentDate']);
            $table->index(['status', 'appointmentDate']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
