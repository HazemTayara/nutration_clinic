{{--
    Partial: محتوى يوم واحد (أشرطة التقدم، زر إضافة وجبة، قائمة الوجبات).
    هذا الملف موجود في: resources/views/diets/_day.blade.php

    المتغيرات:
        $day       — DietDay (مع تحميل meals.mealItems.foodItem.unit)
        $diet      — Diet
        $targets   — مصفوفة ['calories','carbs','protein','fat']
        $foodItems — مجموعة FoodItem للقائمة المنسدلة
--}}

@php
    $dayTotals       = $day->total_nutrition;
    $caloriesPercent = $targets['calories'] > 0 ? min(100, ($dayTotals['calories'] / $targets['calories']) * 100) : 0;
    $carbsPercent    = $targets['carbs']    > 0 ? min(100, ($dayTotals['carbs']    / $targets['carbs'])    * 100) : 0;
    $proteinPercent  = $targets['protein']  > 0 ? min(100, ($dayTotals['protein']  / $targets['protein'])  * 100) : 0;
    $fatPercent      = $targets['fat']      > 0 ? min(100, ($dayTotals['fat']      / $targets['fat'])      * 100) : 0;
@endphp

{{-- Daily Nutrition Target Progress --}}
<div class="target-progress">
    <h5 class="mb-3">التقدم اليومي</h5>
    <div class="row">
        <div class="col-md-3">
            <small>السعرات: {{ round($dayTotals['calories']) }} / {{ round($targets['calories']) }} سعرة</small>
            <div class="progress mb-2" style="height:8px;">
                <div class="progress-bar bg-success" style="width:{{ $caloriesPercent }}%"></div>
            </div>
        </div>
        <div class="col-md-3">
            <small>الكربوهيدرات: {{ round($dayTotals['carbs']) }} / {{ round($targets['carbs']) }} جم</small>
            <div class="progress mb-2" style="height:8px;">
                <div class="progress-bar bg-info" style="width:{{ $carbsPercent }}%"></div>
            </div>
        </div>
        <div class="col-md-3">
            <small>البروتين: {{ round($dayTotals['protein']) }} / {{ round($targets['protein']) }} جم</small>
            <div class="progress mb-2" style="height:8px;">
                <div class="progress-bar bg-primary" style="width:{{ $proteinPercent }}%"></div>
            </div>
        </div>
        <div class="col-md-3">
            <small>الدهون: {{ round($dayTotals['fat']) }} / {{ round($targets['fat']) }} جم</small>
            <div class="progress mb-2" style="height:8px;">
                <div class="progress-bar bg-warning" style="width:{{ $fatPercent }}%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Add Meal Button --}}
<div class="mb-3">
    <button type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addMealModal"
            data-action="open-add-meal">
        <i class="fas fa-plus-circle ms-2"></i>إضافة وجبة
    </button>
</div>

{{-- Meals List --}}
@forelse($day->meals as $meal)
    <div class="meal-card">
        <div class="meal-header">
            <div>
                <span class="meal-type-badge meal-type-{{ $meal->type }}">
                    @switch($meal->type)
                        @case('breakfast') إفطار @break
                        @case('lunch') غداء @break
                        @case('dinner') عشاء @break
                        @case('snack') وجبة خفيفة @break
                        @default {{ ucfirst($meal->type) }}
                    @endswitch
                </span>
            </div>
            <div>
                <span class="ms-3">
                    @php
                        $calories = 0;
                        $protein = 0;
                        $carbs = 0;
                        $fat = 0;

                        foreach ($meal->mealItems as $item) {
                            $calories += round($item->foodItem->calories) * $item->quantity;
                            $protein += round($item->foodItem->protein) * $item->quantity;
                            $carbs += round($item->foodItem->carbs) * $item->quantity;
                            $fat += round($item->foodItem->fat) * $item->quantity;
                        }
                    @endphp

                    <strong>{{ round($calories) }}</strong> سعرة
                    <strong>{{ round($carbs) }}</strong>جم
                    <strong>{{ round($protein) }}</strong>جم
                    <strong>{{ round($fat) }}</strong>جم
                </span>
                <form action="{{ route('meals.destroy', $meal) }}" method="POST" class="d-inline"
                      data-ajax-form data-confirm="حذف هذه الوجبة؟">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Food Items --}}
        @if($meal->mealItems->count() > 0)
            @foreach($meal->mealItems as $item)
                <div class="food-item-row">
                    <div>
                        <strong>{{ $item->foodItem->name }}</strong>
                        <small class="text-muted me-2">
                            {{ round($item->quantity) }} حصة
                        </small>
                    </div>
                    <div>
                        <span class="ms-3">{{ round($item->foodItem->calories) * $item->quantity }} سعرة</span>
                        <span class="ms-3">{{ round($item->foodItem->carbs) * $item->quantity }}جم</span>
                        <span class="ms-3">{{ round($item->foodItem->protein) * $item->quantity }}جم</span>
                        <span class="ms-3">{{ round($item->foodItem->fat) * $item->quantity }}جم</span>
                        <form action="{{ route('meals.items.destroy', [$meal, $item]) }}" method="POST" class="d-inline"
                              data-ajax-form data-confirm="إزالة هذا العنصر؟">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm text-danger" title="إزالة">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="nutrition-summary">
                <div class="row text-center">
                    <div class="col-3">
                        <small class="text-muted">السعرات</small>
                        <strong>{{ round($meal->mealItems->sum(fn($item) => $item->foodItem->calories * $item->quantity)) }} سعرة</strong>
                    </div>
                    <div class="col-3">
                        <small class="text-muted">الكربوهيدرات</small>
                        <strong>{{ round($meal->mealItems->sum(fn($item) => $item->foodItem->carbs * $item->quantity)) }} جم</strong>
                    </div>
                    <div class="col-3">
                        <small class="text-muted">البروتين</small>
                        <strong>{{ round($meal->mealItems->sum(fn($item) => $item->foodItem->protein * $item->quantity)) }} جم</strong>
                    </div>
                    <div class="col-3">
                        <small class="text-muted">الدهون</small>
                        <strong>{{ round($meal->mealItems->sum(fn($item) => $item->foodItem->fat * $item->quantity)) }} جم</strong>
                    </div>
                </div>
            </div>
        @else
            <p class="text-muted text-center py-3">لم تتم إضافة أي عناصر غذائية بعد.</p>
        @endif

        {{-- Add Food Item Form --}}
        <div class="add-item-form">
            <form action="{{ route('meals.items.store', $meal) }}" method="POST" data-ajax-form>
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small">العنصر الغذائي</label>
                        <select name="food_item_id" class="form-select food-item-select" required>
                            <option value="">اختر الطعام</option>
                            @foreach($foodItems as $food)
                                <option value="{{ $food->id }}">{{ $food->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">الكمية</label>
                        <input type="number" step="0.1" min="0.1" name="quantity" class="form-control"
                               placeholder="100" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> إضافة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-utensils fa-3x mb-3 opacity-50"></i>
        <p class="text-muted">لم تتم إضافة وجبات لهذا اليوم بعد.</p>
    </div>
@endforelse