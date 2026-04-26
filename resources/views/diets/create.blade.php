@extends('layouts.app')

@section('title', 'إنشاء خطة غذائية')
@section('page-title', 'إنشاء خطة غذائية لـ ' . $measurement->patient->name)

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
    .measurement-summary {
        background: #F8F9FA;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt ms-2"></i>خطة غذائية جديدة
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">حدد المدة وتاريخ البدء للنظام الغذائي</p>
                </div>
                
                <div class="form-body">
                    {{-- Measurement Summary --}}
                    <div class="measurement-summary">
                        <h6 class="mb-3">تفاصيل القياس</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">الوزن</small>
                                <strong>{{ number_format($measurement->weight_kg, 1) }} كجم</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">مؤشر كتلة الجسم</small>
                                <strong>{{ $measurement->formatted_bmi }} ({{ $measurement->bmi_category }})</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">معدل الأيض النشط المستهدف</small>
                                <strong>{{ round($measurement->aee()) }} سعرة</strong>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('diets.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="patient_measurement_id" value="{{ $measurement->id }}">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">
                                    تاريخ البدء <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="days_count" class="form-label fw-semibold">
                                    عدد الأيام <span class="text-danger">*</span>
                                </label>
                                <select name="days_count" 
                                        id="days_count" 
                                        class="form-select form-select-lg @error('days_count') is-invalid @enderror">
                                    @foreach([7, 10, 14, 15, 21, 28, 30] as $days)
                                        <option value="{{ $days }}" {{ old('days_count', 7) == $days ? 'selected' : '' }}>
                                            {{ $days }} يوم
                                        </option>
                                    @endforeach
                                </select>
                                @error('days_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">الحد الأدنى 7 أيام، الحد الأقصى 30 يوم</small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle ms-2"></i>
                            سيتم حساب تاريخ الانتهاء تلقائياً بناءً على تاريخ البدء وعدد الأيام.
                        </div>

                        <div class="d-flex justify-content-between border-top pt-4">
                            <a href="{{ route('patients.measurements.show', [$measurement->patient_id, $measurement]) }}" 
                               class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-right ms-2"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-check ms-2"></i>إنشاء الخطة الغذائية
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection