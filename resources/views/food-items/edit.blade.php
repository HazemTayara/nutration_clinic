{{-- resources/views/food-items/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل الصنف الغذائي')
@section('page-title', 'تعديل الصنف الغذائي')

@push('styles')
    <style>
        .form-card {
            border: none;
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            background: white;
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            padding: 20px 30px;
            color: white;
        }

        .form-body {
            padding: 30px;
        }

        .required-asterisk {
            color: #dc3545;
            margin-right: 4px;
        }

        .nutrition-section {
            background: #F8F9FA;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
            border: 1px dashed var(--primary-light);
        }

        .usage-stats {
            background: #E8F5E9;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .input-group {
            direction: ltr;
        }
        
        .input-group .input-group-text {
            border-radius: 0 8px 8px 0;
        }
        
        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0">
                            <i class="fas fa-edit ms-2"></i>تعديل: {{ $foodItem->name }}
                        </h4>
                    </div>
                    <div class="form-body">
                        {{-- Usage Stats --}}
                        @php
                            $usedInMeals = $foodItem->mealItems->count() ?? 0;
                            $usedInTemplates = $foodItem->templateItems->count() ?? 0;
                        @endphp

                        @if($usedInMeals > 0 || $usedInTemplates > 0)
                            <div class="usage-stats">
                                <i class="fas fa-info-circle ms-2"></i>
                                <strong>هذا الصنف مستخدم حالياً في:</strong>
                                <ul class="mt-2 mb-0">
                                    @if($usedInMeals > 0)
                                        <li>{{ $usedInMeals }} خطة وجبات</li>
                                    @endif
                                    @if($usedInTemplates > 0)
                                        <li>{{ $usedInTemplates }} قالب</li>
                                    @endif
                                </ul>
                                <small class="text-muted mt-2 d-block">
                                    التغييرات ستؤثر على جميع خطط الوجبات الحالية التي تستخدم هذا الصنف.
                                </small>
                            </div>
                        @endif

                        <form action="{{ route('food-items.update', $foodItem) }}" method="POST" id="foodItemForm">
                            @csrf
                            @method('PUT')

                            {{-- Basic Information --}}
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label fw-semibold">
                                        اسم الصنف <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $foodItem->name) }}"
                                        placeholder="مثال: صدر دجاج، أرز بني، تفاح...">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="food_category_id" class="form-label fw-semibold">
                                        الفئة <span class="required-asterisk">*</span>
                                    </label>
                                    <select name="food_category_id" id="food_category_id"
                                        class="form-select @error('food_category_id') is-invalid @enderror">
                                        <option value="">اختر الفئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('food_category_id', $foodItem->food_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('food_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Portion Information --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="portion_quantity" class="form-label fw-semibold">
                                        كمية الحصة الافتراضية
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('portion_quantity') is-invalid @enderror"
                                        id="portion_quantity" name="portion_quantity"
                                        value="{{ old('portion_quantity', $foodItem->portion_quantity) }}"
                                        placeholder="100">
                                    @error('portion_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">حجم الحصة الافتراضي (مثال: 100)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="unit_id" class="form-label fw-semibold">
                                        وحدة القياس
                                    </label>
                                    <select name="unit_id" id="unit_id"
                                        class="form-select @error('unit_id') is-invalid @enderror">
                                        <option value="">اختر الوحدة</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $foodItem->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle ms-1"></i>
                                        <a href="{{ route('units.create') }}" target="_blank" class="text-decoration-none">
                                            إضافة وحدة جديدة <i class="fas fa-external-link-alt me-1 small"></i>
                                        </a>
                                    </small>
                                </div>
                            </div>

                            {{-- Nutritional Information --}}
                            <div class="nutrition-section">
                                <h5 class="mb-3" style="color: var(--primary-dark); font-weight: 600;">
                                    <i class="fas fa-chart-pie ms-2"></i>القيم الغذائية (لكل حصة)
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="calories" class="form-label fw-semibold">
                                            السعرات الحرارية <span class="required-asterisk">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('calories') is-invalid @enderror"
                                                id="calories" name="calories"
                                                value="{{ old('calories', $foodItem->calories) }}">
                                            <span class="input-group-text">سعرة</span>
                                        </div>
                                        @error('calories')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="protein" class="form-label fw-semibold">
                                            البروتين <span class="required-asterisk">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('protein') is-invalid @enderror"
                                                id="protein" name="protein"
                                                value="{{ old('protein', $foodItem->protein) }}">
                                            <span class="input-group-text">جم</span>
                                        </div>
                                        @error('protein')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="carbs" class="form-label fw-semibold">
                                            الكربوهيدرات <span class="required-asterisk">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('carbs') is-invalid @enderror"
                                                id="carbs" name="carbs"
                                                value="{{ old('carbs', $foodItem->carbs) }}">
                                            <span class="input-group-text">جم</span>
                                        </div>
                                        @error('carbs')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="fat" class="form-label fw-semibold">
                                            الدهون <span class="required-asterisk">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('fat') is-invalid @enderror"
                                                id="fat" name="fat"
                                                value="{{ old('fat', $foodItem->fat) }}">
                                            <span class="input-group-text">جم</span>
                                        </div>
                                        @error('fat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <a href="{{ route('food-items.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-save ms-2"></i>تحديث الصنف الغذائي
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar with timestamps --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-history ms-2" style="color: var(--primary);"></i>معلومات
                        </h5>
                        <p class="mb-2">
                            <i class="far fa-calendar-plus ms-2"></i>
                            تاريخ الإنشاء: {{ $foodItem->created_at->format('Y/m/d H:i') }}
                        </p>
                        <p class="mb-0">
                            <i class="far fa-calendar-check ms-2"></i>
                            آخر تحديث: {{ $foodItem->updated_at->format('Y/m/d H:i') }}
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-calculator ms-2" style="color: var(--accent);"></i>ملخص القيم الغذائية
                        </h5>
                        <div id="macroPreview" class="mb-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span>الكربوهيدرات:</span>
                                <strong id="previewCarbs">0%</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>البروتين:</span>
                                <strong id="previewProtein">0%</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>الدهون:</span>
                                <strong id="previewFat">0%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateMacroPreview() {
            const carbs = parseFloat(document.getElementById('carbs').value) || 0;
            const protein = parseFloat(document.getElementById('protein').value) || 0;
            const fat = parseFloat(document.getElementById('fat').value) || 0;

            const totalCalories = (carbs * 4) + (protein * 4) + (fat * 9);

            if (totalCalories > 0) {
                const carbsPercent = ((carbs * 4) / totalCalories) * 100;
                const proteinPercent = ((protein * 4) / totalCalories) * 100;
                const fatPercent = ((fat * 9) / totalCalories) * 100;

                document.getElementById('previewCarbs').textContent = carbsPercent.toFixed(1) + '%';
                document.getElementById('previewProtein').textContent = proteinPercent.toFixed(1) + '%';
                document.getElementById('previewFat').textContent = fatPercent.toFixed(1) + '%';
            }
        }

        ['carbs', 'protein', 'fat'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateMacroPreview);
        });

        document.addEventListener('DOMContentLoaded', updateMacroPreview);
    </script>
@endpush