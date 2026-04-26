<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\PatientMeasurementController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealItemController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('food-category', FoodCategoryController::class);
Route::resource('units', UnitController::class);
Route::resource('food-items', FoodItemController::class);

Route::resource('patients', PatientController::class);

Route::prefix('patients/{patient}/measurements')->name('patients.measurements.')->group(function () {
    Route::get('/create', [PatientMeasurementController::class, 'create'])->name('create');
    Route::post('/', [PatientMeasurementController::class, 'store'])->name('store');
    Route::get('/{measurement}', [PatientMeasurementController::class, 'show'])->name('show');
    Route::get('/{measurement}/edit', [PatientMeasurementController::class, 'edit'])->name('edit');
    Route::put('/{measurement}', [PatientMeasurementController::class, 'update'])->name('update');
    Route::delete('/{measurement}', [PatientMeasurementController::class, 'destroy'])->name('destroy');
});

// Diet routes
Route::resource('diets', DietController::class)->except(['index', 'edit', 'update']);

// Nested routes for meals and meal items
Route::prefix('diets/{diet}/days/{day}')->name('diets.days.')->group(function () {
    Route::post('meals', [MealController::class, 'store'])->name('meals.store');
});

Route::prefix('meals/{meal}')->name('meals.')->group(function () {
    Route::post('items', [MealItemController::class, 'store'])->name('items.store');
    Route::delete('items/{mealItem}', [MealItemController::class, 'destroy'])->name('items.destroy');
});

Route::delete('meals/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');