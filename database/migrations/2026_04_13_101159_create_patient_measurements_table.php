<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->decimal('height_cm', 5, 2);
            $table->decimal('weight_kg', 5, 2);
            $table->enum('activity_level', ['light', 'housewife', 'student', 'good_movement', 'athletic', 'very_athletic'])->default('light');
            $table->decimal('carb_percentage', 5, 2)->default(50.00);
            $table->decimal('protein_percentage', 5, 2)->default(20.00);
            $table->decimal('fat_percentage', 5, 2)->default(30.00);
            $table->decimal('bee_custom', 8, 2)->nullable();
            $table->decimal('belly', 5, 2)->nullable();
            $table->decimal('lower_abdomen', 5, 2)->nullable();
            $table->decimal('hips', 5, 2)->nullable();
            $table->decimal('right_arm', 5, 2)->nullable();
            $table->decimal('left_arm', 5, 2)->nullable();
            $table->decimal('right_thigh', 5, 2)->nullable();
            $table->decimal('left_thigh', 5, 2)->nullable();
            $table->decimal('right_calf', 5, 2)->nullable();
            $table->decimal('left_calf', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_measurements');
    }
};