<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    protected $fillable = [
        'diet_day_id',
        'type',
    ];

    const TYPES = ['breakfast', 'lunch', 'dinner', 'snack'];

    public function dietDay(): BelongsTo
    {
        return $this->belongsTo(DietDay::class);
    }

    public function mealItems(): HasMany
    {
        return $this->hasMany(MealItem::class);
    }

    // Helper: get total nutrition for the meal
    public function getTotalNutritionAttribute(): array
    {
        $totals = ['calories' => 0, 'carbs' => 0, 'protein' => 0, 'fat' => 0];
        
        foreach ($this->mealItems as $item) {
            $nutrition = $item->foodItem->calculateNutrition($item->quantity);
            $totals['calories'] += $nutrition['calories'];
            $totals['carbs'] += $nutrition['carbs'];
            $totals['protein'] += $nutrition['protein'];
            $totals['fat'] += $nutrition['fat'];
        }
        
        return array_map(fn($v) => round($v, 2), $totals);
    }

    // Helper: count snacks for validation
    public static function countSnacksForDay(int $dietDayId): int
    {
        return self::where('diet_day_id', $dietDayId)
            ->where('type', 'snack')
            ->count();
    }
}