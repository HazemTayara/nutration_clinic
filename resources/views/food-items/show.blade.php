{{-- resources/views/food-items/show.blade.php --}}
@extends('layouts.app')

@section('title', $foodItem->name)
@section('page-title', 'تفاصيل الصنف الغذائي')

@push('styles')
    <style>
        .detail-card {
            border: none;
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            background: white;
        }

        .nutrition-badge-large {
            padding: 15px;
            border-radius: 16px;
            text-align: center;
        }

        .macro-bar {
            height: 8px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8">
                <div class="detail-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h2 class="mb-2">{{ $foodItem->name }}</h2>
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="fas fa-folder ms-1"></i>
                                    {{ $foodItem->category->name ?? 'غير مصنف' }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('food-items.edit', $foodItem) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit ms-2"></i>تعديل
                                </a>
                            </div>
                        </div>

                        <h4 class="mb-3">المعلومات الغذائية (لكل حصة)</h4>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="nutrition-badge-large" style="background: #FFF3E0;">
                                    <h3 class="mb-0" style="color: #E65100;">
                                        {{ number_format($foodItem->calories, 1) }}
                                    </h3>
                                    <small>سعرات حرارية (سعرة)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="nutrition-badge-large" style="background: #E8F5E9;">
                                    <h3 class="mb-0" style="color: #2E7D32;">
                                        {{ number_format($foodItem->protein, 1) }}جم
                                    </h3>
                                    <small>بروتين</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="nutrition-badge-large" style="background: #E3F2FD;">
                                    <h3 class="mb-0" style="color: #1565C0;">
                                        {{ number_format($foodItem->carbs, 1) }}جم
                                    </h3>
                                    <small>كربوهيدرات</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="nutrition-badge-large" style="background: #FCE4EC;">
                                    <h3 class="mb-0" style="color: #C2185B;">
                                        {{ number_format($foodItem->fat, 1) }}جم
                                    </h3>
                                    <small>دهون</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="mt-4">حجم الحصة</h5>
                                <p class="mb-0">
                                    {{ $foodItem->portion_quantity }} {{ $foodItem->unit->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="detail-card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">إجراءات سريعة</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('food-items.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list ms-2"></i>العودة للمكتبة
                            </a>
                            <button onclick="history.back()" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-right ms-2"></i>رجوع
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection