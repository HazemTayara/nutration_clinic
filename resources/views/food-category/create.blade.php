{{-- resources/views/food-category/create.blade.php --}}
@extends('layouts.app')

@section('title', 'إضافة فئة غذائية')
@section('page-title', 'إضافة فئة غذائية')

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
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-plus-circle ms-2"></i>فئة غذائية جديدة</h4>
                    </div>
                    <div class="form-body">
                        <form action="{{ route('food-category.store') }}" method="POST">
                            @csrf

                            {{-- Name Field --}}
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    اسم الفئة <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="مثال: خضروات، فواكه، ألبان..." autofocus>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle ms-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">اختر اسماً وصفياً لسهولة التعرف عليه.</small>
                            </div>

                            {{-- Preview / Info --}}
                            <div class="alert alert-light border mb-4">
                                <i class="fas fa-info-circle ms-2" style="color: var(--primary);"></i>
                                <strong>ملاحظة:</strong> يمكنك إضافة عناصر غذائية لهذه الفئة لاحقاً من قسم العناصر الغذائية.
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <a href="{{ route('food-category.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save ms-2"></i>حفظ الفئة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection