<?php

namespace App\Http\Controllers;

use App\Models\Diet;
use App\Models\Patient;
use App\Models\PatientMeasurement;
use App\Models\DietDay;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DietController extends Controller
{
    public function create(Request $request)
    {
        $measurementId = $request->query('measurement_id');
        $measurement = PatientMeasurement::with('patient')->findOrFail($measurementId);

        return view('diets.create', compact('measurement'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_measurement_id' => 'required|exists:patient_measurements,id',
            'start_date' => 'required|date',
            'days_count' => 'required|integer|min:7|max:30',
        ]);

        $measurement = PatientMeasurement::findOrFail($validated['patient_measurement_id']);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($validated['days_count'] - 1);

        // Create diet
        $diet = Diet::create([
            'patient_id' => $measurement->patient_id,
            'patient_measurement_id' => $measurement->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Generate diet days
        for ($i = 0; $i < $validated['days_count']; $i++) {
            DietDay::create([
                'diet_id' => $diet->id,
                'date' => $startDate->copy()->addDays($i),
            ]);
        }

        return redirect()->route('diets.show', $diet)
            ->with('success', 'Diet plan created successfully!');
    }

    // In DietController.php show method
    public function show(Diet $diet)
    {
        $diet->load([
            'patient',
            'measurement',
            'dietDays' => function ($q) {
                $q->orderBy('date');
            },
            'dietDays.meals' => function ($q) {
                $q->orderBy('type');
            },
            'dietDays.meals.mealItems.foodItem.unit'
        ]);

        // Get target macros from measurement
        $targets = [
            'calories' => $diet->measurement->aee(),
            'carbs' => $diet->measurement->carb(),
            'protein' => $diet->measurement->protein(),
            'fat' => $diet->measurement->fat(),
        ];

        // Get all food items for dropdowns
        $foodItems = \App\Models\FoodItem::with('unit')->orderBy('name')->get();

        return view('diets.show', compact('diet', 'targets', 'foodItems'));
    }

    public function destroy(Diet $diet)
    {
        $patientId = $diet->patient_id;
        $diet->delete();

        return redirect()->route('patients.show', $patientId)
            ->with('success', 'Diet deleted successfully!');
    }
}