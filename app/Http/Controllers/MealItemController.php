<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RendersDietDay;
use App\Models\FoodItem;
use App\Models\Meal;
use App\Models\MealItem;
use Illuminate\Http\Request;

class MealItemController extends Controller
{
    use RendersDietDay;

    public function store(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity'     => 'required|numeric|min:0.1',
        ]);

        $foodItem = FoodItem::findOrFail($validated['food_item_id']);

        MealItem::create([
            'meal_id'      => $meal->id,
            'food_item_id' => $foodItem->id,
            'quantity'     => $validated['quantity'],
        ]);

        return $this->dayResponse($request, $meal->dietDay, true, 'Food item added to meal.');
    }

    public function destroy(Request $request, Meal $meal, MealItem $mealItem)
    {
        $day = $meal->dietDay;
        $mealItem->delete();

        return $this->dayResponse($request, $day, true, 'Item removed.');
    }
}
