{{-- resources/views/units/index.blade.php --}}
@extends('layouts.app')

@section('title', 'الوحدات')
@section('page-title', 'وحدات القياس')

@push('styles')
    <style>
        .unit-table th {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .unit-table td {
            vertical-align: middle;
        }

        .unit-badge {
            background: #E8F5E9;
            color: #2E7D32;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 10px;
            transition: var(--transition);
            margin: 0 3px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #94A3B8;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">إدارة وحدات القياس لحصص الطعام</p>
            </div>
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle ms-2"></i>إضافة وحدة
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle ms-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Units Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                @if($units->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 unit-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>الاسم</th>
                                    <th>الأصناف الغذائية</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-end pe-4">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="fw-medium">{{ $unit->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $unit->food_items_count }} صنف
                                            </span>
                                        </td>
                                        <td>{{ $unit->created_at->format('Y/m/d') }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('units.edit', $unit) }}"
                                                class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="tooltip"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($unit->canBeDeleted())
                                                <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                                    onclick="confirmDelete({{ $unit->id }}, '{{ $unit->name }}')"
                                                    data-bs-toggle="tooltip" title="حذف">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $unit->id }}" action="{{ route('units.destroy', $unit) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary action-btn" disabled
                                                    data-bs-toggle="tooltip"
                                                    title="لا يمكن الحذف - مستخدمة من قبل {{ $unit->food_items_count }} صنف غذائي">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-weight-scale fa-4x mb-3" style="opacity: 0.5;"></i>
                        <h4>لا توجد وحدات</h4>
                        <p class="mb-4">ابدأ بإضافة وحدات القياس مثل الجرام، الملليلتر، القطعة، إلخ.</p>
                        <a href="{{ route('units.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle ms-2"></i>إضافة أول وحدة
                        </a>
                    </div>
                @endif
            </div>

            @if($units->hasPages())
                <div class="card-footer bg-transparent border-0 pt-3 pb-2">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        function confirmDelete(unitId, unitName) {
            Swal.fire({
                title: 'حذف الوحدة؟',
                html: `هل أنت متأكد من رغبتك في حذف <strong>${unitName}</strong>؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذفها!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${unitId}`).submit();
                }
            });
        }
    </script>
@endpush