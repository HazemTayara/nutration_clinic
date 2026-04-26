{{-- resources/views/units/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل الوحدة')
@section('page-title', 'تعديل الوحدة: ' . $unit->name)

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

        .usage-warning {
            background: #FFF3E0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            border-right: 4px solid #FF9800;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0">
                            <i class="fas fa-edit ms-2"></i>تعديل الوحدة
                        </h4>
                        <p class="mb-0 mt-2 opacity-75">تحديث تفاصيل وحدة القياس</p>
                    </div>

                    <div class="form-body">
                        {{-- Usage Warning --}}
                        @if($unit->foodItems->count() > 0)
                            <div class="usage-warning">
                                <i class="fas fa-exclamation-triangle ms-2"></i>
                                <strong>هذه الوحدة مستخدمة من قبل {{ $unit->foodItems->count() }} صنف غذائي.</strong>
                                <br>
                                <small class="text-muted">التغييرات ستؤثر على جميع الأصناف الغذائية المرتبطة.</small>
                            </div>
                        @endif

                        <form action="{{ route('units.update', $unit) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    اسم الوحدة <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $unit->name) }}"
                                    placeholder="مثال: جرام، ملليلتر، قطعة">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info --}}
                            <div class="alert alert-light border mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle fa-2x ms-3" style="color: var(--primary);"></i>
                                    <div>
                                        <strong>معلومات الوحدة</strong>
                                        <p class="mb-0 mt-1 small">
                                            تاريخ الإنشاء: {{ $unit->created_at->format('Y/m/d H:i') }}<br>
                                            آخر تحديث: {{ $unit->updated_at->format('Y/m/d H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save ms-2"></i>تحديث الوحدة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection