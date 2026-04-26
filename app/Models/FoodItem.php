<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodItem extends Model
{
    protected $fillable = [
        'name',
        'food_category_id',
        'calories',
        'carbs',
        'protein',
        'fat',
        'portion_quantity',
        'unit_id',
    ];

    protected $casts = [
        'calories' => 'decimal:2',
        'carbs' => 'decimal:2',
        'protein' => 'decimal:2',
        'fat' => 'decimal:2',
        'portion_quantity' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function mealItems(): HasMany
    {
        return $this->hasMany(MealItem::class);
    }

    public function templateItems(): HasMany
    {
        return $this->hasMany(MealTemplateItem::class);
    }

    // Helper: calculate nutrition for specific quantity
    public function calculateNutrition(float $quantity): array
    {
        $factor = $quantity / 100;
        return [
            'calories' => round($this->calories * $factor, 2),
            'carbs' => round($this->carbs * $factor, 2),
            'protein' => round($this->protein * $factor, 2),
            'fat' => round($this->fat * $factor, 2),
        ];
    }
}