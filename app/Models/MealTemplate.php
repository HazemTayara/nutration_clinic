<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MealTemplateItem::class);
    }

    // Helper: get total nutrition for the template
    public function getTotalNutritionAttribute(): array
    {
        $totals = ['calories' => 0, 'carbs' => 0, 'protein' => 0, 'fat' => 0];
        
        foreach ($this->items as $item) {
            $nutrition = $item->foodItem->calculateNutrition($item->quantity);
            $totals['calories'] += $nutrition['calories'];
            $totals['carbs'] += $nutrition['carbs'];
            $totals['protein'] += $nutrition['protein'];
            $totals['fat'] += $nutrition['fat'];
        }
        
        return array_map(fn($v) => round($v, 2), $totals);
    }

    // Helper: apply template to a meal
    public function applyToMeal(Meal $meal): void
    {
        foreach ($this->items as $templateItem) {
            MealItem::create([
                'meal_id' => $meal->id,
                'food_item_id' => $templateItem->food_item_id,
                'quantity' => $templateItem->quantity,
            ]);
        }
    }
}