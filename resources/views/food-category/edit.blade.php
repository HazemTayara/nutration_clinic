{{-- resources/views/food-category/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل فئة غذائية')
@section('page-title', 'تعديل فئة غذائية')

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

        .stats-badge {
            background: #f0f7f0;
            border-radius: 30px;
            padding: 8px 16px;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-edit ms-2"></i>تعديل الفئة: {{ $foodCategory->name }}</h4>
                    </div>
                    <div class="form-body">
                        <form action="{{ route('food-category.update', $foodCategory) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Name Field --}}
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    اسم الفئة <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $foodCategory->name) }}"
                                    placeholder="مثال: خضروات، فواكه، ألبان...">
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle ms-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Category Info --}}
                            <div class="stats-badge mb-4">
                                <i class="fas fa-utensils ms-2" style="color: var(--primary);"></i>
                                <span class="fw-medium">العناصر الغذائية في هذه الفئة:</span>
                                <span class="badge bg-primary me-2">{{ $foodCategory->foodItems->count() }}</span>
                            </div>

                            {{-- Warning if category has items --}}
                            @if($foodCategory->foodItems->count() > 0)
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle ms-3 fa-lg"></i>
                                    <div>
                                        تحتوي هذه الفئة على {{ $foodCategory->foodItems->count() }} عنصر غذائي.
                                        تغيير الاسم لن يؤثر على العناصر نفسها.
                                    </div>
                                </div>
                            @endif

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <a href="{{ route('food-category.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-save ms-2"></i>تحديث الفئة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection