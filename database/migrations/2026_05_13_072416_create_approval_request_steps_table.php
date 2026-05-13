<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_request_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->unsignedTinyInteger('step_order');
            $table->foreignId('approver_id')->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('actioned_at')->nullable();
            $table->timestamps();

            $table->unique(['approval_request_id', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_request_steps');
    }
};