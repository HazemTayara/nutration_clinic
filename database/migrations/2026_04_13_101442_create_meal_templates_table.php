<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_templates');
    }
};