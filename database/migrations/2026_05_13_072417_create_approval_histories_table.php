<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->foreignId('approval_request_step_id')->nullable()->constrained('approval_request_steps')->nullOnDelete();
            $table->foreignId('actor_id')->constrained('users');
            $table->string('action', 50);
            $table->unsignedTinyInteger('step_order')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('actioned_at')->useCurrent();
            // ไม่มี updated_at เพราะ immutable
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};