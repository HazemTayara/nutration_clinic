{{-- resources/views/patients/show.blade.php --}}
@extends('layouts.app')

@section('title', $patient->name . ' - الملف الشخصي')
@section('page-title', 'الملف الشخصي للمريض')

@push('styles')
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            border-radius: 24px;
            padding: 30px;
            color: white;
            margin-bottom: 24px;
        }

        .measurement-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .measurement-card:hover {
            box-shadow: var(--shadow-md);
        }

        .stat-box {
            background: #F8F9FA;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1E293B;
        }

        .stat-label {
            color: #64748B;
            font-size: 14px;
            margin-top: 5px;
        }

        .timeline {
            position: relative;
            padding-right: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            right: -30px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid white;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            right: -25px;
            top: 12px;
            width: 2px;
            height: calc(100% - 12px);
            background: var(--light-gray);
        }

        .timeline-item:last-child::after {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $patient->name }}</h2>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-calendar-alt ms-2"></i>
                        {{ $patient->date_of_birth->format('F d, Y') }}
                        ({{ $patient->age }} سنة)
                        <span class="mx-3">•</span>
                        <i class="fas fa-{{ $patient->gender ? 'mars' : 'venus' }} ms-2"></i>
                        {{ $patient->gender ? 'ذكر' : 'أنثى' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('patients.measurements.create', $patient) }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus-circle ms-2"></i>إضافة قياس
                    </a>
                </div>
            </div>
        </div>

        {{-- Latest Measurement Summary --}}
        @php
            $latestMeasurement = $patient->latestMeasurement;
        @endphp

        @if($latestMeasurement)
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="stat-value">{{ $latestMeasurement->formatted_bmi }}</div>
                        <div class="stat-label">مؤشر كتلة الجسم</div>
                        <span class="badge bg-{{ $latestMeasurement->bmi_color }} mt-2">
                            {{ $latestMeasurement->bmi_category }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="stat-value">{{ number_format($latestMeasurement->weight_kg, 1) }}</div>
                        <div class="stat-label">الوزن (كجم)</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="stat-value">{{ $latestMeasurement->formatted_bee }}</div>
                        <div class="stat-label">معدل الأيض الأساسي (سعرة)</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="stat-value">{{ $latestMeasurement->formatted_aee }}</div>
                        <div class="stat-label">معدل الأيض النشط (سعرة)</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Measurements History --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h4 class="mb-4">
                    <i class="fas fa-history ms-2" style="color: var(--primary);"></i>
                    سجل القياسات
                </h4>

                @if($patient->measurements->count() > 0)
                    <div class="timeline">
                        @foreach($patient->measurements as $measurement)
                            <div class="timeline-item">
                                <div class="measurement-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1">حمية {{ $loop->iteration }}</h5>
                                            <small class="text-muted">
                                                <i class="far fa-calendar ms-1"></i>
                                                {{ $measurement->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $measurement->bmi_color }} px-3 py-2">
                                            مؤشر كتلة الجسم: {{ $measurement->formatted_bmi }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">الوزن</small>
                                            <strong>{{ number_format($measurement->weight_kg, 1) }} كجم</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">مستوى النشاط</small>
                                            <strong>{{ $measurement->activity_level }}</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">تعديل السعرات</small>
                                            <strong>{{ $measurement->bee_custom }} سعرة</strong>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <a href="{{ route('patients.measurements.show', [$patient, $measurement]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye ms-1"></i>عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-weight-scale fa-3x mb-3" style="opacity: 0.3;"></i>
                        <p class="text-muted">لم يتم تسجيل أي قياسات بعد.</p>
                        <a href="{{ route('patients.measurements.create', $patient) }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle ms-2"></i>إضافة أول قياس
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection