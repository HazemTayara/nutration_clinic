{{-- resources/views/food-items/create.blade.php --}}
@extends('layouts.app')

@section('title', 'إضافة صنف غذائي')
@section('page-title', 'إضافة صنف غذائي جديد')

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

        .nutrition-title {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .input-group-text {
            background-color: #F1F5F9;
            border: 1px solid #E2E8F0;
            color: #64748B;
            font-weight: 500;
        }

        .quick-fill-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .quick-fill-btn {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            background: white;
            border: 1px solid var(--light-gray);
            color: var(--gray-text);
            cursor: pointer;
            transition: var(--transition);
        }

        .quick-fill-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: white;
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
                        <h4 class="mb-0"><i class="fas fa-apple-alt ms-2"></i>إضافة صنف غذائي جديد</h4>
                    </div>
                    <div class="form-body">
                        <form action="{{ route('food-items.store') }}" method="POST" id="foodItemForm">
                            @csrf

                            {{-- Basic Information --}}
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label fw-semibold">
                                        اسم الصنف <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="مثال: صدر دجاج، أرز بني، تفاح..." autofocus>
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
                                            <option value="{{ $category->id }}" {{ old('food_category_id') == $category->id ? 'selected' : '' }}>
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
                                        value="{{ old('portion_quantity', 100) }}" placeholder="100">
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
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
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

                            {{-- Nutritional Information (per portion) --}}
                            <div class="nutrition-section">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="nutrition-title mb-0">
                                        <i class="fas fa-chart-pie ms-2"></i>القيم الغذائية (لكل حصة)
                                    </h5>
                                    <div class="quick-fill-buttons">
                                        <button type="button" class="quick-fill-btn" onclick="fillExample('chicken')">
                                            <i class="fas fa-drumstick-bite ms-1"></i>دجاج
                                        </button>
                                        <button type="button" class="quick-fill-btn" onclick="fillExample('rice')">
                                            <i class="fas fa-seedling ms-1"></i>أرز
                                        </button>
                                        <button type="button" class="quick-fill-btn" onclick="fillExample('apple')">
                                            <i class="fas fa-apple-alt ms-1"></i>تفاح
                                        </button>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-3">
                                        <label for="calories" class="form-label fw-semibold">
                                            السعرات الحرارية <span class="required-asterisk">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('calories') is-invalid @enderror"
                                                id="calories" name="calories"
                                                value="{{ old('calories') }}" placeholder="0.00">
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
                                                value="{{ old('protein') }}" placeholder="0.00">
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
                                                value="{{ old('carbs') }}" placeholder="0.00">
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
                                                id="fat" name="fat" value="{{ old('fat') }}"
                                                placeholder="0.00">
                                            <span class="input-group-text">جم</span>
                                        </div>
                                        @error('fat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Nutrition Preview --}}
                            <div class="alert alert-info mt-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calculator fa-2x ms-3"></i>
                                    <div>
                                        <strong>معاينة توزيع العناصر الغذائية:</strong>
                                        <div id="macroPreview" class="mt-1">
                                            <span class="badge bg-primary ms-2" id="previewCarbs">كربوهيدرات: 0%</span>
                                            <span class="badge bg-success ms-2" id="previewProtein">بروتين: 0%</span>
                                            <span class="badge bg-danger" id="previewFat">دهون: 0%</span>
                                        </div>
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
                                        <i class="fas fa-save ms-2"></i>حفظ الصنف الغذائي
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Quick Tips Sidebar --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-lightbulb ms-2" style="color: var(--accent);"></i>نصائح سريعة
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                أدخل القيم لكل حصة
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                استخدم مصادر موثوقة للدقة
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                يمكنك تعديل القيم في أي وقت
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-exclamation-triangle text-warning ms-2"></i>
                                تأكد من القيم قبل الحفظ
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-database ms-2" style="color: var(--primary);"></i>الإحصائيات الحالية
                        </h5>
                        <p class="mb-2">إجمالي الأصناف الغذائية: <strong>{{ $totalItems ?? 0 }}</strong></p>
                        <p class="mb-0">الفئات المتاحة: <strong>{{ $categories->count() }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Real-time macro percentage calculator
        function updateMacroPreview() {
            const carbs = parseFloat(document.getElementById('carbs').value) || 0;
            const protein = parseFloat(document.getElementById('protein').value) || 0;
            const fat = parseFloat(document.getElementById('fat').value) || 0;

            const totalCalories = (carbs * 4) + (protein * 4) + (fat * 9);

            if (totalCalories > 0) {
                const carbsPercent = ((carbs * 4) / totalCalories) * 100;
                const proteinPercent = ((protein * 4) / totalCalories) * 100;
                const fatPercent = ((fat * 9) / totalCalories) * 100;

                document.getElementById('previewCarbs').textContent = `كربوهيدرات: ${carbsPercent.toFixed(1)}%`;
                document.getElementById('previewProtein').textContent = `بروتين: ${proteinPercent.toFixed(1)}%`;
                document.getElementById('previewFat').textContent = `دهون: ${fatPercent.toFixed(1)}%`;
            }
        }

        // Attach listeners
        ['carbs', 'protein', 'fat'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateMacroPreview);
        });

        // Quick fill examples
        function fillExample(type) {
            const examples = {
                chicken: { cal: 165, prot: 31, carb: 0, fat: 3.6 },
                rice: { cal: 130, prot: 2.7, carb: 28, fat: 0.3 },
                apple: { cal: 52, prot: 0.3, carb: 14, fat: 0.2 }
            };

            const data = examples[type];
            if (data) {
                document.getElementById('calories').value = data.cal;
                document.getElementById('protein').value = data.prot;
                document.getElementById('carbs').value = data.carb;
                document.getElementById('fat').value = data.fat;
                updateMacroPreview();

                // Small animation feedback
                Swal.fire({
                    title: 'تم تحميل المثال!',
                    text: `تم إدراج قيم ${type === 'chicken' ? 'الدجاج' : type === 'rice' ? 'الأرز' : 'التفاح'}`,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', updateMacroPreview);
    </script>
@endpush