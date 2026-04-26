
{{-- resources/views/patients/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل مريض')
@section('page-title', 'تعديل مريض: ' . $patient->name)

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

        .gender-selector {
            display: flex;
            gap: 15px;
        }

        .gender-option {
            flex: 1;
            text-align: center;
            padding: 20px;
            border: 2px solid var(--light-gray);
            border-radius: 16px;
            cursor: pointer;
            transition: var(--transition);
        }

        .gender-option:hover {
            border-color: var(--primary-light);
            background: #F8F9FA;
        }

        .gender-option.selected {
            border-color: var(--primary);
            background: #E8F5E9;
        }

        .gender-option i {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .gender-option.male i {
            color: #1976D2;
        }

        .gender-option.female i {
            color: #C2185B;
        }

        .info-card {
            background: #F8F9FA;
            border-radius: 16px;
            padding: 20px;
            margin-top: 20px;
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
                            <i class="fas fa-user-edit ms-2"></i>تعديل معلومات المريض
                        </h4>
                        <p class="mb-0 mt-2 opacity-75">تحديث المعلومات الأساسية لـ {{ $patient->name }}</p>
                    </div>

                    <div class="form-body">
                        <form action="{{ route('patients.update', $patient) }}" method="POST" id="patientForm">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    الاسم الكامل <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $patient->name) }}"
                                        placeholder="أدخل الاسم الكامل للمريض" autofocus>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    الجنس <span class="required-asterisk">*</span>
                                </label>
                                <div class="gender-selector">
                                    <div class="gender-option male {{ old('gender', $patient->gender) == '1' ? 'selected' : '' }}"
                                        onclick="selectGender(1)">
                                        <i class="fas fa-mars"></i>
                                        <h5 class="mb-1">ذكر</h5>
                                        <small class="text-muted">♂</small>
                                    </div>
                                    <div class="gender-option female {{ old('gender', $patient->gender) == '0' ? 'selected' : '' }}"
                                        onclick="selectGender(0)">
                                        <i class="fas fa-venus"></i>
                                        <h5 class="mb-1">أنثى</h5>
                                        <small class="text-muted">♀</small>
                                    </div>
                                </div>
                                <input type="hidden" name="gender" id="gender"
                                    value="{{ old('gender', $patient->gender) }}">
                                @error('gender')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div class="mb-4">
                                <label for="date_of_birth" class="form-label fw-semibold">
                                    تاريخ الميلاد <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date"
                                        class="form-control form-control-lg @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}">
                                </div>
                                @error('date_of_birth')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle ms-1"></i>
                                    العمر الحالي: {{ $patient->age }} سنة
                                </small>
                            </div>

                            {{-- Patient Stats Info --}}
                            <div class="info-card">
                                <h6 class="mb-3">
                                    <i class="fas fa-chart-bar ms-2" style="color: var(--primary);"></i>
                                    إحصائيات المريض
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">إجمالي القياسات</small>
                                        <h5>{{ $patient->measurements->count() }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">الحميات الغذائية</small>
                                        <h5>{{ $patient->diets->count() }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">عضو منذ</small>
                                        <h5>{{ $patient->created_at->format('M Y') }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save ms-2"></i>تحديث المريض
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function selectGender(gender) {
            document.getElementById('gender').value = gender;

            // Update visual selection
            document.querySelectorAll('.gender-option').forEach(opt => {
                opt.classList.remove('selected');
            });

            if (gender === 1) {
                document.querySelector('.gender-option.male').classList.add('selected');
            } else {
                document.querySelector('.gender-option.female').classList.add('selected');
            }
        }

        // Set initial selection
        document.addEventListener('DOMContentLoaded', function () {
            const genderValue = document.getElementById('gender').value;
            if (genderValue !== '') {
                selectGender(parseInt(genderValue));
            }
        });
    </script>
@endpush
