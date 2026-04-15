<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('availability_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('doctorId')->index();
            $table->tinyInteger('dayOfWeek');
            $table->time('startTime');
            $table->time('endTime');
            $table->boolean('isActive')->default(true);
            $table->timestamps();
            $table->unique(['doctorId', 'dayOfWeek', 'startTime']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('availability_templates');
    }
};
