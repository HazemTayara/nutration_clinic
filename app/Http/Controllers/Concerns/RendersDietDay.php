<?php

namespace App\Http\Controllers\Concerns;

use App\Models\DietDay;
use App\Models\FoodItem;
use Illuminate\Http\Request;

/**
 * Shared helper used by MealController & MealItemController
 * so AJAX responses can ship back a freshly-rendered day block.
 *
 * Save this file at: app/Http/Controllers/Concerns/RendersDietDay.php
 */
trait RendersDietDay
{
    protected function dayResponse(Request $request, DietDay $day, bool $success, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'day_id'  => $day->id,
                'html'    => $success ? $this->renderDayPartial($day) : null,
            ], $success ? 200 : 422);
        }

        return $success
            ? back()->with('success', $message)
            : back()->with('error', $message);
    }

    protected function renderDayPartial(DietDay $day): string
    {
        // Re-fetch fresh state with all the relations the partial needs
        $day->load([
            'meals' => fn ($q) => $q->orderBy('type'),
            'meals.mealItems.foodItem.unit',
        ]);

        $diet = $day->diet()->with(['patient', 'measurement'])->firstOrFail();

        $targets = [
            'calories' => $diet->measurement->aee(),
            'carbs'    => $diet->measurement->carb(),
            'protein'  => $diet->measurement->protein(),
            'fat'      => $diet->measurement->fat(),
        ];

        $foodItems = FoodItem::with('unit')->orderBy('name')->get();

        return view('diets._day', compact('day', 'diet', 'targets', 'foodItems'))->render();
    }
}
