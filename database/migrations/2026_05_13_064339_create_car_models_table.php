<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('brand');
            $table->string('name');
            $table->integer('year');
            $table->enum('body_type', [
                'Sedan',
                'SUV',
                'Pickup',
                'Hatchback',
                'Van',
                'Coupe',
                'Wagon',
                'Convertible',
                'MPV',
            ]);
            $table->decimal('base_price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->string('deletion_reason')->nullable();
            $table->string('deletion_detail')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};