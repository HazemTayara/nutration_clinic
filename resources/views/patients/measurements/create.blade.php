{{-- resources/views/patients/measurements/create.blade.php --}}
@extends('layouts.app')

@section('title', 'إضافة قياس')
@section('page-title', 'إضافة قياس لـ ' . $patient->name)

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
            padding: 24px 32px;
            color: white;
        }

        .form-body {
            padding: 32px;
        }

        .required-asterisk {
            color: #dc3545;
            margin-right: 4px;
        }

        .patient-info-banner {
            background: #F8F9FA;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-right: 4px solid var(--primary);
        }

        .calculation-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 20px;
            color: white;
        }

        .macro-input-group {
            background: #F8F9FA;
            border-radius: 12px;
            padding: 15px;
            margin-top: 15px;
        }

        .macro-slider {
            width: 100%;
        }

        .macro-value {
            font-weight: 700;
            color: var(--primary);
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
                            <i class="fas fa-weight-scale ms-2"></i>القياس
                        </h4>
                        <p class="mb-0 mt-2 opacity-75">تسجيل قياسات المريض وحساب الاحتياجات الغذائية</p>
                    </div>

                    <div class="form-body">
                        <div class="patient-info-banner">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-1">{{ $patient->name }}</h5>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-calendar ms-1"></i>
                                        {{ $patient->age }} سنة
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-{{ $patient->gender ? 'mars' : 'venus' }} ms-1"></i>
                                        {{ $patient->gender ? 'ذكر' : 'أنثى' }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-md-start">
                                    @if($patient->latestMeasurement)
                                        <span class="badge bg-info">
                                            <i class="fas fa-history ms-1"></i>
                                            آخر قياس: {{ $patient->latestMeasurement->created_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle ms-1"></i>
                                            أول قياس
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('patients.measurements.store', $patient) }}" method="POST"
                            id="measurementForm">
                            @csrf

                            {{-- Basic Measurements --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="weight_kg" class="form-label fw-semibold">
                                        الوزن <span class="required-asterisk">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" step="0.01" min="1" max="300"
                                            class="form-control @error('weight_kg') is-invalid @enderror" id="weight_kg"
                                            name="weight_kg"
                                            value="{{ old('weight_kg', $patient->latestMeasurement?->weight_kg) }}"
                                            placeholder="0.00">
                                        <span class="input-group-text">كجم</span>
                                    </div>
                                    @error('weight_kg')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="height_cm" class="form-label fw-semibold">
                                        الطول <span class="required-asterisk">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" step="0.01" min="50" max="250"
                                            class="form-control @error('height_cm') is-invalid @enderror" id="height_cm"
                                            name="height_cm"
                                            value="{{ old('height_cm', $patient->latestMeasurement?->height_cm) }}"
                                            placeholder="0.00">
                                        <span class="input-group-text">سم</span>
                                    </div>
                                    @error('height_cm')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Activity Level --}}
                            <div class="mb-4">
                                <label for="activity_level" class="form-label fw-semibold">
                                    مستوى النشاط <span class="required-asterisk">*</span>
                                </label>
                                <select name="activity_level" id="activity_level"
                                    class="form-select form-select-lg @error('activity_level') is-invalid @enderror">
                                    @foreach(\App\Models\PatientMeasurement::activityLevelOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('activity_level', 'light') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_level')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle ms-1"></i>
                                    هذا يحدد معامل النشاط (قيمة F) لحساب BEE
                                </small>
                            </div>

                            {{-- BEE Custom (Calorie Deficit) --}}
                            <div class="mb-4">
                                <label for="bee_custom" class="form-label fw-semibold">
                                    تعديل السعرات (سعرة) <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="1" min="100" max="1000"
                                        class="form-control @error('bee_custom') is-invalid @enderror" id="bee_custom"
                                        name="bee_custom" value="{{ old('bee_custom', 500) }}" placeholder="500">
                                    <span class="input-group-text">سعرة</span>
                                </div>
                                @error('bee_custom')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle ms-1"></i>
                                    النطاق الموصى به: 100-1000 سعرة عجز
                                </small>
                            </div>

                            {{-- Custom Macro Percentages --}}
                            <div class="macro-input-group">
                                <h6 class="mb-3">
                                    <i class="fas fa-chart-pie ms-2"></i>توزيع العناصر الغذائية الكبرى (%)
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="carb_percentage" class="form-label fw-semibold">
                                            الكربوهيدرات %
                                        </label>
                                        <input type="range" class="form-range macro-slider" id="carb_slider" min="45"
                                            max="65" step="1" value="{{ old('carb_percentage', 50) }}"
                                            oninput="updateMacroValue('carb', this.value)">
                                        <div class="input-group mt-2">
                                            <input type="number" class="form-control" id="carb_percentage"
                                                name="carb_percentage" value="{{ old('carb_percentage', 50) }}" step="1"
                                                min="45" max="65" oninput="updateMacroSlider('carb', this.value)">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="protein_percentage" class="form-label fw-semibold">
                                            البروتين %
                                        </label>
                                        <input type="range" class="form-range macro-slider" id="protein_slider" min="15"
                                            max="30" step="1" value="{{ old('protein_percentage', 20) }}"
                                            oninput="updateMacroValue('protein', this.value)">
                                        <div class="input-group mt-2">
                                            <input type="number" class="form-control" id="protein_percentage"
                                                name="protein_percentage" value="{{ old('protein_percentage', 20) }}"
                                                step="1" min="15" max="30"
                                                oninput="updateMacroSlider('protein', this.value)">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="fat_percentage" class="form-label fw-semibold">
                                            الدهون %
                                        </label>
                                        <input type="range" class="form-range macro-slider" id="fat_slider" min="20"
                                            max="40" step="1" value="{{ old('fat_percentage', 30) }}"
                                            oninput="updateMacroValue('fat', this.value)">
                                        <div class="input-group mt-2">
                                            <input type="number" class="form-control" id="fat_percentage"
                                                name="fat_percentage" value="{{ old('fat_percentage', 30) }}" step="1"
                                                min="20" max="40" oninput="updateMacroSlider('fat', this.value)">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <small>المجموع: <span id="totalPercentage">100</span>%</small>
                                </div>
                            </div>

                            {{-- Calculation Preview --}}
                            <div class="calculation-preview" id="calculationPreview">
                                <h6 class="mb-3">
                                    <i class="fas fa-calculator ms-2"></i>معاينة الحسابات الغذائية
                                </h6>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <small class="opacity-75">مؤشر كتلة الجسم</small>
                                        <h5 id="previewBmi">-</h5>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <small class="opacity-75">معدل الأيض الأساسي</small>
                                        <h5 id="previewBmr">-</h5>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <small class="opacity-75">الطاقة الأساسية</small>
                                        <h5 id="previewBee">-</h5>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <small class="opacity-75">الطاقة الفعلية</small>
                                        <h5 id="previewAee">-</h5>
                                    </div>
                                </div>
                                <div class="row border-top pt-3 mt-2">
                                    <div class="col-md-4 col-6">
                                        <small class="opacity-75">كربوهيدرات</small>
                                        <h6 id="previewCarbs">-</h6>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <small class="opacity-75">بروتين</small>
                                        <h6 id="previewProtein">-</h6>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <small class="opacity-75">دهون</small>
                                        <h6 id="previewFat">-</h6>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="opacity-75">احتياج الماء</small>
                                        <h6 id="previewWater">-</h6>
                                    </div>
                                </div>
                            </div>

                            {{-- Body Measurements --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="fas fa-ruler ms-2" style="color: var(--primary);"></i>
                                        قياسات الجسم (سم)
                                    </h6>

                                    <div class="row g-3">
                                        {{-- Upper Body --}}
                                        <div class="col-md-4">
                                            <label for="belly" class="form-label fw-semibold">البطن</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('belly') is-invalid @enderror" id="belly"
                                                    name="belly" value="{{ old('belly') }}" placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('belly')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="lower_abdomen" class="form-label fw-semibold">أسفل البطن</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('lower_abdomen') is-invalid @enderror"
                                                    id="lower_abdomen" name="lower_abdomen"
                                                    value="{{ old('lower_abdomen') }}" placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('lower_abdomen')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="hips" class="form-label fw-semibold">الأرداف</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('hips') is-invalid @enderror" id="hips"
                                                    name="hips" value="{{ old('hips') }}" placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('hips')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Arms --}}
                                        <div class="col-md-6">
                                            <label for="right_arm" class="form-label fw-semibold">الذراع الأيمن</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('right_arm') is-invalid @enderror"
                                                    id="right_arm" name="right_arm" value="{{ old('right_arm') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('right_arm')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="left_arm" class="form-label fw-semibold">الذراع الأيسر</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('left_arm') is-invalid @enderror"
                                                    id="left_arm" name="left_arm" value="{{ old('left_arm') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('left_arm')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Thighs --}}
                                        <div class="col-md-6">
                                            <label for="right_thigh" class="form-label fw-semibold">الفخذ الأيمن</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('right_thigh') is-invalid @enderror"
                                                    id="right_thigh" name="right_thigh" value="{{ old('right_thigh') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('right_thigh')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="left_thigh" class="form-label fw-semibold">الفخذ الأيسر</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('left_thigh') is-invalid @enderror"
                                                    id="left_thigh" name="left_thigh" value="{{ old('left_thigh') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('left_thigh')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Calves --}}
                                        <div class="col-md-6">
                                            <label for="right_calf" class="form-label fw-semibold">الساق اليمنى</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('right_calf') is-invalid @enderror"
                                                    id="right_calf" name="right_calf" value="{{ old('right_calf') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('right_calf')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="left_calf" class="form-label fw-semibold">الساق اليسرى</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('left_calf') is-invalid @enderror"
                                                    id="left_calf" name="left_calf" value="{{ old('left_calf') }}"
                                                    placeholder="0.00">
                                                <span class="input-group-text">سم</span>
                                            </div>
                                            @error('left_calf')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <small class="text-muted mt-3 d-block">
                                        <i class="fas fa-info-circle ms-1"></i>
                                        جميع القياسات اختيارية ويمكن ملؤها تدريجياً
                                    </small>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save ms-2"></i>حفظ القياس
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar Tips --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-lightbulb ms-2" style="color: var(--accent);"></i>دليل القياس
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                خذ القياسات في نفس الوقت
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                استخدم موازين معايرة للدقة
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success ms-2"></i>
                                اضبط نسب العناصر الغذائية بناءً على أهداف المريض
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-chart-line ms-2" style="color: var(--primary);"></i>معاملات النشاط
                        </h5>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><strong>نشاط خفيف</strong> - 1.2</li>
                            <li class="mb-2"><strong>ربة منزل</strong> - 1.3</li>
                            <li class="mb-2"><strong>طالب جامعي</strong> - 1.4</li>
                            <li class="mb-2"><strong>حركة جيدة</strong> - 1.5</li>
                            <li class="mb-2"><strong>رياضي</strong> - 1.6</li>
                            <li class="mb-2"><strong>رياضي جداً</strong> - 1.7</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const patientAge = {{ $patient->age }};
        const patientGender = {{ $patient->gender ? 'true' : 'false' }};

        // Activity level to F-value mapping
        const activityFValues = {
            'light': 1.2,
            'housewife': 1.3,
            'student': 1.4,
            'good_movement': 1.5,
            'athletic': 1.6,
            'very_athletic': 1.7
        };

        // Sync macro sliders and number inputs
        function updateMacroValue(type, value) {
            document.getElementById(type + '_percentage').value = value;
            document.getElementById(type + '_slider').value = value;
            updateTotalPercentage();
            updateCalculations();
        }

        function updateMacroSlider(type, value) {
            value = parseFloat(value) || 0;
            value = Math.min(Math.max(value,
                document.getElementById(type + '_slider').min),
                document.getElementById(type + '_slider').max);
            document.getElementById(type + '_percentage').value = value;
            document.getElementById(type + '_slider').value = value;
            updateTotalPercentage();
            updateCalculations();
        }

        function updateTotalPercentage() {
            const carb = parseFloat(document.getElementById('carb_percentage').value) || 0;
            const protein = parseFloat(document.getElementById('protein_percentage').value) || 0;
            const fat = parseFloat(document.getElementById('fat_percentage').value) || 0;
            const total = carb + protein + fat;
            document.getElementById('totalPercentage').textContent = total.toFixed(1);
        }

        // BMR calculation
        function calculateBMR(weight, height, age, isMale) {
            if (isMale) {
                return 66 + (13.7 * weight) + (5 * height) - (6.8 * age);
            } else {
                return 655 + (9.6 * weight) + (1.8 * height) - (4.7 * age);
            }
        }

        // Update all preview calculations
        function updateCalculations() {
            const weight = parseFloat(document.getElementById('weight_kg').value) || 0;
            const height = parseFloat(document.getElementById('height_cm').value) || 0;
            const activityLevel = document.getElementById('activity_level').value;
            const fValue = activityFValues[activityLevel] || 1.2;
            const beeCustom = parseFloat(document.getElementById('bee_custom').value) || 500;
            const carbPct = parseFloat(document.getElementById('carb_percentage').value) || 50;
            const proteinPct = parseFloat(document.getElementById('protein_percentage').value) || 20;
            const fatPct = parseFloat(document.getElementById('fat_percentage').value) || 30;

            if (weight > 0 && height > 0) {
                // BMI
                const bmi = weight / ((height / 100) ** 2);
                document.getElementById('previewBmi').textContent = bmi.toFixed(1);

                // BMR
                const bmr = calculateBMR(weight, height, patientAge, patientGender);
                document.getElementById('previewBmr').textContent = Math.round(bmr) + ' سعرة';

                // BEE
                const bee = bmr * fValue;
                document.getElementById('previewBee').textContent = Math.round(bee) + ' سعرة';

                // AEE
                const aee = bee - beeCustom;
                document.getElementById('previewAee').textContent = Math.round(aee) + ' سعرة';

                // Macros
                if (aee > 0) {
                    const carbs = ((aee * carbPct) / 100) / 4;
                    const protein = ((aee * proteinPct) / 100) / 4;
                    const fat = ((aee * fatPct) / 100) / 9;

                    document.getElementById('previewCarbs').textContent = Math.round(carbs) + ' جم';
                    document.getElementById('previewProtein').textContent = Math.round(protein) + ' جم';
                    document.getElementById('previewFat').textContent = Math.round(fat) + ' جم';
                }

                // Water
                const water = weight <= 30 ? 1750 : 1750 + ((weight - 30) * 15);
                document.getElementById('previewWater').textContent = Math.round(water) + ' مل';
            }
        }

        // Attach listeners
        ['weight_kg', 'height_cm', 'bee_custom'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateCalculations);
        });
        document.getElementById('activity_level').addEventListener('change', updateCalculations);
        ['carb_percentage', 'protein_percentage', 'fat_percentage'].forEach(id => {
            document.getElementById(id).addEventListener('input', function () {
                updateMacroSlider(id.replace('_percentage', ''), this.value);
            });
        });

        // Initial calculation
        document.addEventListener('DOMContentLoaded', () => {
            updateTotalPercentage();
            updateCalculations();
        });
    </script>
@endpush