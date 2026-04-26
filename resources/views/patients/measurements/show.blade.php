{{-- resources/views/patients/measurements/show.blade.php --}}
@extends('layouts.app')

@section('title', 'تفاصيل القياس')
@section('page-title', 'تفاصيل القياس')

@push('styles')
    <style>
        .measurement-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            border-radius: 24px;
            padding: 30px;
            color: white;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            height: 100%;
        }

        .stat-value-large {
            font-size: 36px;
            font-weight: 700;
            color: #1E293B;
        }

        .stat-label {
            color: #64748B;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .macro-bar {
            height: 8px;
            border-radius: 4px;
            background: var(--light-gray);
            overflow: hidden;
            margin: 15px 0;
        }

        .macro-segment {
            height: 100%;
            float: right;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .action-button {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            transition: var(--transition);
        }

        .bmi-indicator-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: #F8F9FA;
            border: 4px solid;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        {{-- Header --}}
        <div class="measurement-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $patient->name }}</h2>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-calendar-alt ms-2"></i>
                        قياس بتاريخ {{ $measurement->created_at->format('Y/m/d') }}
                        <span class="mx-3">•</span>
                        <i class="fas fa-{{ $patient->gender ? 'mars' : 'venus' }} ms-2"></i>
                        {{ $patient->gender ? 'ذكر' : 'أنثى' }}, {{ $patient->age }} سنة
                    </p>
                </div>
                <div class="col-md-4 text-md-start mt-3 mt-md-0">
                    <a href="{{ route('patients.measurements.edit', [$patient, $measurement]) }}"
                        class="btn btn-light ms-2">
                        <i class="fas fa-edit ms-2"></i>تعديل
                    </a>
                    <button onclick="confirmDelete()" class="btn btn-outline-light">
                        <i class="fas fa-trash-alt ms-2"></i>حذف
                    </button>
                    <form id="delete-form" action="{{ route('patients.measurements.destroy', [$patient, $measurement]) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        {{-- Basic Measurements --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-label">الوزن</div>
                    <div class="stat-value-large">{{ number_format($measurement->weight_kg, 1) }}</div>
                    <small class="text-muted">كيلوجرام</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-label">الطول</div>
                    <div class="stat-value-large">{{ number_format($measurement->height_cm, 1) }}</div>
                    <small class="text-muted">سنتيمتر</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-label">مؤشر كتلة الجسم</div>
                    <div class="stat-value-large text-{{ $measurement->bmi_color }}">
                        {{ $measurement->formatted_bmi }}
                    </div>
                    <small class="text-{{ $measurement->bmi_color }}">{{ $measurement->bmi_category }}</small>
                </div>
            </div>
        </div>

        {{-- Energy Calculations --}}
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="stat-card">
                    <h5 class="mb-3">
                        <i class="fas fa-fire ms-2" style="color: #E65100;"></i>حسابات الطاقة
                    </h5>
                    <div class="info-row">
                        <span>معدل الأيض الأساسي (BMR):</span>
                        <strong>{{ $measurement->formatted_bmr }} سعرة</strong>
                    </div>
                    <div class="info-row">
                        <span>مستوى النشاط:</span>
                        <strong>{{ $measurement->activity_level_label }} (F = {{ $measurement->f_value }})</strong>
                    </div>
                    <div class="info-row">
                        <span>الطاقة الأساسية المستهلكة (BEE):</span>
                        <strong>{{ $measurement->formatted_bee }} سعرة</strong>
                    </div>
                    <div class="info-row">
                        <span>تعديل السعرات:</span>
                        <strong>{{ $measurement->bee_custom }} سعرة</strong>
                    </div>
                    <div class="info-row">
                        <span>الطاقة الفعلية المستهلكة (AEE):</span>
                        <strong class="text-primary">{{ $measurement->formatted_aee }} سعرة</strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="stat-card">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-pie ms-2" style="color: var(--primary);"></i>أهداف العناصر الغذائية
                    </h5>

                    @php
                        $carbs = $measurement->carb();
                        $protein = $measurement->protein();
                        $fat = $measurement->fat();
                        $totalCalories = ($carbs * 4) + ($protein * 4) + ($fat * 9);
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle ms-2" style="color: #1565C0;"></i>كربوهيدرات
                                ({{ $measurement->carb_percentage }}%)</span>
                            <strong>{{ round($carbs) }} جم</strong>
                        </div>
                        <div class="macro-bar">
                            <div class="macro-segment"
                                style="width: {{ $measurement->carb_percentage }}%; background: #1565C0;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle ms-2" style="color: #2E7D32;"></i>بروتين
                                ({{ $measurement->protein_percentage }}%)</span>
                            <strong>{{ round($protein) }} جم</strong>
                        </div>
                        <div class="macro-bar">
                            <div class="macro-segment"
                                style="width: {{ $measurement->protein_percentage }}%; background: #2E7D32;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle ms-2" style="color: #C2185B;"></i>دهون
                                ({{ $measurement->fat_percentage }}%)</span>
                            <strong>{{ round($fat) }} جم</strong>
                        </div>
                        <div class="macro-bar">
                            <div class="macro-segment"
                                style="width: {{ $measurement->fat_percentage }}%; background: #C2185B;"></div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 mb-0">
                        <div class="d-flex justify-content-between">
                            <span>إجمالي السعرات من العناصر الغذائية:</span>
                            <strong>{{ round($totalCalories) }} سعرة</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body Measurements --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stat-card">
                    <h5 class="mb-3">
                        <i class="fas fa-ruler ms-2" style="color: var(--primary);"></i>قياسات الجسم
                    </h5>

                    @if(
                            $measurement->belly || $measurement->lower_abdomen || $measurement->hips ||
                            $measurement->right_arm || $measurement->left_arm || $measurement->right_thigh ||
                            $measurement->left_thigh || $measurement->right_calf || $measurement->left_calf
                        )

                        <div class="row g-3">
                            @if($measurement->belly)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">البطن</small>
                                        <strong class="fs-5">{{ number_format($measurement->belly, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->lower_abdomen)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">أسفل البطن</small>
                                        <strong class="fs-5">{{ number_format($measurement->lower_abdomen, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->hips)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الأرداف</small>
                                        <strong class="fs-5">{{ number_format($measurement->hips, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->right_arm)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الذراع الأيمن</small>
                                        <strong class="fs-5">{{ number_format($measurement->right_arm, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->left_arm)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الذراع الأيسر</small>
                                        <strong class="fs-5">{{ number_format($measurement->left_arm, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->right_thigh)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الفخذ الأيمن</small>
                                        <strong class="fs-5">{{ number_format($measurement->right_thigh, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->left_thigh)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الفخذ الأيسر</small>
                                        <strong class="fs-5">{{ number_format($measurement->left_thigh, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->right_calf)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الساق اليمنى</small>
                                        <strong class="fs-5">{{ number_format($measurement->right_calf, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif

                            @if($measurement->left_calf)
                                <div class="col-md-3">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block">الساق اليسرى</small>
                                        <strong class="fs-5">{{ number_format($measurement->left_calf, 1) }} سم</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-info-circle ms-2"></i>
                            لم يتم تسجيل قياسات الجسم بعد
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Additional Requirements --}}
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="stat-card">
                    <h5 class="mb-3">
                        <i class="fas fa-tint ms-2" style="color: #0288D1;"></i>احتياج الماء
                    </h5>
                    <div class="text-center py-3">
                        <div class="stat-value-large">{{ $measurement->formatted_water }}</div>
                        <small class="text-muted">ملليلتر في اليوم</small>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle ms-1"></i>
                        بناءً على حساب وزن الجسم
                    </p>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="stat-card">
                    <h5 class="mb-3">
                        <i class="fas fa-history ms-2" style="color: var(--accent);"></i>معلومات
                    </h5>
                    <div class="info-row">
                        <span>تاريخ الإنشاء:</span>
                        <strong>{{ $measurement->created_at->format('Y/m/d H:i') }}</strong>
                    </div>
                    <div class="info-row">
                        <span>آخر تحديث:</span>
                        <strong>{{ $measurement->updated_at->format('Y/m/d H:i') }}</strong>
                    </div>
                    <div class="info-row">
                        <span>النظام الغذائي:</span>
                        <strong>
                            @if($measurement->diet)
                                <a href="#" class="text-decoration-none">
                                    {{ $measurement->diet->name }}
                                    <i class="fas fa-external-link-alt me-1 small"></i>
                                </a>
                            @else
                                <span class="text-muted">لم يتم إنشاؤه بعد</span>
                            @endif
                        </strong>
                    </div>

                    @if(!$measurement->diet)
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100">
                                <i class="fas fa-utensils ms-2"></i>إنشاء نظام غذائي
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right ms-2"></i>العودة لملف المريض
            </a>

            <div>
                <a href="{{ route('patients.measurements.edit', [$patient, $measurement]) }}"
                    class="btn btn-outline-primary ms-2">
                    <i class="fas fa-edit ms-2"></i>تعديل القياس
                </a>
                <button onclick="confirmDelete()" class="btn btn-outline-danger">
                    <i class="fas fa-trash-alt ms-2"></i>حذف
                </button>
            </div>

            @if(!$measurement->diet)
                <a href="{{ route('diets.create', ['measurement_id' => $measurement->id]) }}" class="btn btn-success">
                    <i class="fas fa-calendar-plus ms-2"></i>إنشاء خطة غذائية
                </a>
            @else
                <a href="{{ route('diets.show', $measurement->diet) }}" class="btn btn-primary">
                    <i class="fas fa-eye ms-2"></i>عرض الخطة الغذائية
                </a>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'حذف القياس؟',
                html: `هل أنت متأكد من رغبتك في حذف هذا القياس؟<br>
                       <small class="text-danger">لا يمكن التراجع عن هذا الإجراء.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>
@endpush