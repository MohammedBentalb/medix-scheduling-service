<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('topic', 100);
            $table->json('payload');
            $table->string('status', 20)->default('pending');
            $table->smallInteger('attempts')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['status', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('outbox_events');
    }
};
