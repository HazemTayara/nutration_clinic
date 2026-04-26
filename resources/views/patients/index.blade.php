
{{-- resources/views/patients/index.blade.php --}}
@extends('layouts.app')

@section('title', 'المرضى')
@section('page-title', 'دليل المرضى')

@push('styles')
<style>
    .patient-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .patient-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .patient-avatar {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        font-weight: 600;
    }
    
    .patient-info h5 {
        margin-bottom: 4px;
        color: #1E293B;
        font-weight: 600;
    }
    
    .patient-meta {
        font-size: 13px;
        color: #64748B;
    }
    
    .stats-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .bmi-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-left: 6px;
    }
    
    .filter-bar {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    
    .view-toggle .btn {
        padding: 10px 16px;
        border-radius: 12px;
    }
    
    .empty-state {
        padding: 80px 20px;
        text-align: center;
        background: white;
        border-radius: 24px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">
                <i class="fas fa-users ms-2"></i>
                {{ $patients->total() }} مريض
            </p>
        </div>
        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-user-plus ms-2"></i>إضافة مريض جديد
        </a>
    </div>

    {{-- Filters --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search ms-1"></i>البحث عن مريض
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           name="search" 
                           placeholder="ابحث بالاسم..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-venus-mars ms-1"></i>الجنس
                    </label>
                    <select name="gender" class="form-select">
                        <option value="">الكل</option>
                        <option value="1" {{ request('gender') === '1' ? 'selected' : '' }}>ذكر</option>
                        <option value="0" {{ request('gender') === '0' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter ms-2"></i>تصفية
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo-alt ms-2"></i>إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle ms-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Patients Grid --}}
    @if($patients->count() > 0)
        <div class="row">
            @foreach($patients as $patient)
                <div class="col-xl-4 col-lg-6">
                    <div class="patient-card">
                        <div class="d-flex mb-3">
                            <div class="patient-avatar ms-3">
                                {{ strtoupper(mb_substr($patient->name, 0, 1)) }}
                            </div>
                            <div class="patient-info flex-grow-1">
                                <h5>{{ $patient->name }}</h5>
                                <div class="patient-meta">
                                    <i class="fas fa-calendar-alt ms-1"></i>
                                    {{ $patient->date_of_birth->format('M d, Y') }} 
                                    ({{ $patient->age }} سنة)
                                    <br>
                                    <i class="fas {{ $patient->gender ? 'fa-mars text-primary' : 'fa-venus text-danger' }} ms-1"></i>
                                    {{ $patient->gender ? 'ذكر' : 'أنثى' }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- Latest Measurement Summary --}}
                        @php
                            $latestMeasurement = $patient->latestMeasurement;
                        @endphp
                        
                        @if($latestMeasurement)
                            <div class="border-top pt-3 mt-2">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">الوزن</small>
                                        <strong>{{ number_format($latestMeasurement->weight_kg, 1) }} كجم</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">الطول</small>
                                        <strong>{{ number_format($latestMeasurement->height_cm, 1) }} سم</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">مؤشر كتلة الجسم</small>
                                        <strong>
                                            <span class="bmi-indicator bg-{{ $latestMeasurement->bmi_color }}"></span>
                                            {{ $latestMeasurement->formatted_bmi }}
                                        </strong>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $latestMeasurement->bmi_color }} bg-opacity-10 text-{{ $latestMeasurement->bmi_color }} w-100 py-2">
                                        {{ $latestMeasurement->bmi_category }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="border-top pt-3 mt-2">
                                <p class="text-muted text-center mb-2">
                                    <i class="fas fa-info-circle ms-1"></i>
                                    لا توجد قياسات بعد
                                </p>
                            </div>
                        @endif
                        
                        {{-- Actions --}}
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('patients.show', $patient) }}" 
                               class="btn btn-outline-primary flex-grow-1">
                                <i class="fas fa-eye ms-1"></i>عرض
                            </a>
                            <a href="{{ route('patients.measurements.create', $patient) }}" 
                               class="btn btn-primary flex-grow-1">
                                <i class="fas fa-weight-scale ms-1"></i>إضافة قياس
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.edit', $patient) }}">
                                            <i class="fas fa-edit ms-2"></i>تعديل المريض
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.show', $patient) }}">
                                            <i class="fas fa-history ms-2"></i>عرض السجل
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" 
                                                onclick="confirmDelete({{ $patient->id }}, '{{ $patient->name }}')">
                                            <i class="fas fa-trash-alt ms-2"></i>حذف
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <form id="delete-form-{{ $patient->id }}" 
                              action="{{ route('patients.destroy', $patient) }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        @if($patients->hasPages())
            <div class="mt-4">
                {{ $patients->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-users fa-4x mb-4" style="opacity: 0.3; color: var(--primary);"></i>
            <h3>لم يتم العثور على مرضى</h3>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'gender']))
                    لا يوجد مرضى يطابقون معايير البحث. حاول تعديل معايير البحث.
                @else
                    ابدأ بإضافة أول مريض إلى العيادة.
                @endif
            </p>
            @if(!request()->hasAny(['search', 'gender']))
                <a href="{{ route('patients.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus ms-2"></i>إضافة أول مريض
                </a>
            @else
                <a href="{{ route('patients.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-redo-alt ms-2"></i>مسح التصفية
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(patientId, patientName) {
        Swal.fire({
            title: 'حذف المريض؟',
            html: `هل أنت متأكد من حذف <strong>${patientName}</strong>؟<br>
                   <small class="text-danger">سيؤدي هذا أيضاً إلى حذف جميع القياسات والخطط الغذائية.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${patientId}`).submit();
            }
        });
    }
</script>
@endpush
