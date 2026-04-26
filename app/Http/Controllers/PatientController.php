<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('latestMeasurement');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        $patients = $query->orderBy('name')->paginate(12);
        
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|boolean',
            'date_of_birth' => 'required|date|before:today',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.measurements.create', $patient)
            ->with('success', 'Patient created! Now add their first measurement.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['measurements' => function($query) {
            $query->latest();
        }]);
        
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|boolean',
            'date_of_birth' => 'required|date|before:today',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully!');
    }
}