<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $categories = FoodCategory::withCount('foodItems')->paginate(10);
        return view('food-category.index', compact('categories'));
    }

    public function create()
    {
        return view('food-category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:food_categories'
        ]);

        FoodCategory::create($validated);

        return redirect()->route('food-category.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(FoodCategory $foodCategory)
    {
        return view('food-category.edit', compact('foodCategory'));
    }

    public function update(Request $request, FoodCategory $foodCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:food_categories,name,' . $foodCategory->id
        ]);

        $foodCategory->update($validated);

        return redirect()->route('food-category.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(FoodCategory $foodCategory)
    {
        $foodCategory->delete();
        return redirect()->route('food-category.index')
            ->with('success', 'Category deleted successfully!');
    }
}