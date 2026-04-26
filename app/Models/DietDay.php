<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DietDay extends Model
{
    protected $fillable = [
        'diet_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function diet(): BelongsTo
    {
        return $this->belongsTo(Diet::class);
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }

    // Helper: get total nutrition for the day
    public function getTotalNutritionAttribute(): array
    {
        $totals = ['calories' => 0, 'carbs' => 0, 'protein' => 0, 'fat' => 0];
        
        foreach ($this->meals as $meal) {
            $mealTotals = $meal->total_nutrition;
            $totals['calories'] += $mealTotals['calories'];
            $totals['carbs'] += $mealTotals['carbs'];
            $totals['protein'] += $mealTotals['protein'];
            $totals['fat'] += $mealTotals['fat'];
        }
        
        return array_map(fn($v) => round($v, 2), $totals);
    }
}