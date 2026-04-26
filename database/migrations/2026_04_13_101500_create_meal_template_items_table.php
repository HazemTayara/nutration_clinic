<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_item_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_template_items');
    }
};