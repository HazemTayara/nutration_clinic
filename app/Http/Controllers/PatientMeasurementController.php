<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientMeasurement;
use Illuminate\Http\Request;

class PatientMeasurementController extends Controller
{
    public function create(Patient $patient)
    {
        return view('patients.measurements.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'height_cm' => 'required|numeric|min:50|max:250',
            'weight_kg' => 'required|numeric|min:1|max:300',
            'activity_level' => 'required|in:light,housewife,student,good_movement,athletic,very_athletic',
            'bee_custom' => 'required|numeric|min:100|max:1000',
            'carb_percentage' => 'required|numeric|min:45|max:65',
            'protein_percentage' => 'required|numeric|min:15|max:30',
            'fat_percentage' => 'required|numeric|min:20|max:40',
            'belly' => 'required|numeric|min:1|max:500',
            'lower_abdomen' => 'required|numeric|min:1|max:500',
            'hips' => 'required|numeric|min:1|max:500',
            'right_arm' => 'required|numeric|min:1|max:500',
            'left_arm' => 'required|numeric|min:1|max:500',
            'right_thigh' => 'required|numeric|min:1|max:500',
            'left_thigh' => 'required|numeric|min:1|max:500',
            'right_calf' => 'required|numeric|min:1|max:500',
            'left_calf' => 'required|numeric|min:1|max:500',
        ]);

        $validated['patient_id'] = $patient->id;

        // Optional: ensure percentages sum to 100 (or close)
        $total = $validated['carb_percentage'] + $validated['protein_percentage'] + $validated['fat_percentage'];
        if (abs($total - 100) > 0.1) {
            return back()->withInput()->withErrors(['macro_total' => 'Macronutrient percentages must sum to 100%']);
        }

        $measurement = PatientMeasurement::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Measurement saved! You can now create a diet plan.');
    }

    public function show(Patient $patient, PatientMeasurement $measurement)
    {
        return view('patients.measurements.show', compact('patient', 'measurement'));
    }

    public function edit(Patient $patient, PatientMeasurement $measurement)
    {
        return view('patients.measurements.edit', compact('patient', 'measurement'));
    }

    public function update(Request $request, Patient $patient, PatientMeasurement $measurement)
    {
        $validated = $request->validate([
            'height_cm' => 'required|numeric|min:50|max:250',
            'weight_kg' => 'required|numeric|min:1|max:300',
            'activity_level' => 'required|in:light,housewife,student,good_movement,athletic,very_athletic',
            'bee_custom' => 'required|numeric|min:100|max:1000',
            'carb_percentage' => 'required|numeric|min:45|max:65',
            'protein_percentage' => 'required|numeric|min:15|max:30',
            'fat_percentage' => 'required|numeric|min:20|max:40',
            'belly' => 'required|numeric|min:1|max:500',
            'lower_abdomen' => 'required|numeric|min:1|max:500',
            'hips' => 'required|numeric|min:1|max:500',
            'right_arm' => 'required|numeric|min:1|max:500',
            'left_arm' => 'required|numeric|min:1|max:500',
            'right_thigh' => 'required|numeric|min:1|max:500',
            'left_thigh' => 'required|numeric|min:1|max:500',
            'right_calf' => 'required|numeric|min:1|max:500',
            'left_calf' => 'required|numeric|min:1|max:500',
        ]);

        $validated['patient_id'] = $patient->id;

        // Optional: ensure percentages sum to 100 (or close)
        $total = $validated['carb_percentage'] + $validated['protein_percentage'] + $validated['fat_percentage'];
        if (abs($total - 100) > 0.1) {
            return back()->withInput()->withErrors(['macro_total' => 'Macronutrient percentages must sum to 100%']);
        }

        $measurement->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Measurement updated successfully!');
    }

    public function destroy(Patient $patient, PatientMeasurement $measurement)
    {
        $measurement->delete();

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Measurement deleted successfully!');
    }
}