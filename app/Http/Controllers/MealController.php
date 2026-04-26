<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RendersDietDay;
use App\Models\Diet;
use App\Models\DietDay;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use RendersDietDay;

    public function store(Request $request, Diet $diet, DietDay $day)
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', Meal::TYPES),
        ]);

        // Validate snack limit
        if ($validated['type'] === 'snack') {
            $snackCount = Meal::where('diet_day_id', $day->id)
                ->where('type', 'snack')
                ->count();

            if ($snackCount >= 3) {
                return $this->dayResponse($request, $day, false, 'Maximum 3 snacks per day allowed.');
            }
        }

        Meal::create([
            'diet_day_id' => $day->id,
            'type'        => $validated['type'],
        ]);

        return $this->dayResponse($request, $day, true, 'Meal added successfully.');
    }

    public function destroy(Request $request, Meal $meal)
    {
        $day = $meal->dietDay;
        $meal->delete();

        return $this->dayResponse($request, $day, true, 'Meal deleted.');
    }
}
