<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Models\Unit;
class FoodItemController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodItem::with('category');

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('food_category_id', $request->category);
        }

        // Unit filter
        if ($request->filled('unit')) {
            $query->where('unit_id', $request->unit);
        }

        $foodItems = $query->orderBy('name')->paginate(15);
        $categories = FoodCategory::withCount('foodItems')->get();
        $categoriesCount = $categories->count();

        return view('food-items.index', compact('foodItems', 'categories', 'categoriesCount'));
    }

    public function create()
    {
        $categories = FoodCategory::orderBy('name')->get();
        $totalItems = FoodItem::count();
        $units = Unit::orderBy('name')->get();

        return view('food-items.create', compact('categories', 'totalItems', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'food_category_id' => 'required|exists:food_categories,id',
            'calories' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'portion_quantity' => 'required|numeric|min:0',
            'unit_id' => 'required|exists:units,id',
        ]);

        FoodItem::create($validated);

        return redirect()->route('food-items.index')
            ->with('success', 'Food item created successfully!');
    }

    public function show(FoodItem $foodItem)
    {
        $foodItem->load('category');
        $foodItem->load('unit');
        return view('food-items.show', compact('foodItem'));
    }

    public function edit(FoodItem $foodItem)
    {
        $categories = FoodCategory::orderBy('name')->get();
        $foodItem->load(['mealItems', 'templateItems']);
        $units = Unit::orderBy('name')->get();

        return view('food-items.edit', compact('foodItem', 'categories', 'units'));
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'food_category_id' => 'required|exists:food_categories,id',
            'calories' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'portion_quantity' => 'required|numeric|min:0',
            'unit_id' => 'required|exists:units,id',
        ]);

        $foodItem->update($validated);

        return redirect()->route('food-items.index')
            ->with('success', 'Food item updated successfully!');
    }

    public function destroy(FoodItem $foodItem)
    {
        $foodItem->delete();

        return redirect()->route('food-items.index')
            ->with('success', 'Food item deleted successfully!');
    }
}