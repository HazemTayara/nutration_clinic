<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealTemplateItem extends Model
{
    protected $fillable = [
        'meal_template_id',
        'food_item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(MealTemplate::class, 'meal_template_id');
    }

    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function getNutritionAttribute(): array
    {
        return $this->foodItem->calculateNutrition($this->quantity);
    }
}