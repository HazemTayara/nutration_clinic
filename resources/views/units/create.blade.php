{{-- resources/views/units/create.blade.php --}}
@extends('layouts.app')

@section('title', 'إضافة وحدة')
@section('page-title', 'إضافة وحدة قياس')

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
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0">
                            <i class="fas fa-weight-scale ms-2"></i>وحدة قياس جديدة
                        </h4>
                        <p class="mb-0 mt-2 opacity-75">أضف وحدة لقياس حصص الطعام</p>
                    </div>

                    <div class="form-body">
                        <form action="{{ route('units.store') }}" method="POST">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    اسم الوحدة <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="مثال: جرام، ملليلتر، قطعة" autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right ms-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save ms-2"></i>حفظ الوحدة
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
    </script>
@endpush