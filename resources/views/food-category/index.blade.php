{{-- resources/views/food-category/index.blade.php --}}
@extends('layouts.app')

@section('title', 'الفئات الغذائية')
@section('page-title', 'الفئات الغذائية')

@push('styles')
    <style>
        .category-table th {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            border-bottom: 2px solid var(--primary);
        }

        .category-table td {
            vertical-align: middle;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 10px;
            transition: var(--transition);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #94A3B8;
        }

        :root {
            --pagination-radius: 14px;
            --pagination-glow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            --pagination-transition: all 0.2s ease;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 0.9rem;
            background: transparent;
            color: var(--heading-color);
            text-decoration: none;
            border-radius: var(--pagination-radius);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--pagination-transition);
            border: 2px solid transparent;
            cursor: pointer;
        }

        .page-link:not(.active-page):not(.prev-next):not(.ellipsis) {
            background: white;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .page-link:not(.active-page):not(.prev-next):not(.ellipsis):hover {
            background: var(--accent-color);
            color: #4a5568;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(99, 102, 241, 0.25);
        }

        .page-item.active .page-link,
        .page-link.active-page {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(99, 102, 241, 0.3);
            font-weight: 700;
        }

        .page-link.prev-next {
            background: white;
            border: 2px solid #e2e8f0;
            min-width: 42px;
            padding: 0;
            border-radius: 0px 0px;
        }

        .page-link.prev-next:hover:not(.disabled .page-link) {
            transform: translateY(-2px);
        }

        .page-item.disabled .page-link {
            background: #f7fafc;
            color: #a0aec0;
            border-color: #e2e8f0;
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            pointer-events: none;
        }

        .page-link.ellipsis {
            background: transparent;
            border: none;
            color: #a0aec0;
            min-width: auto;
            padding: 0 0.25rem;
            font-size: 1.1rem;
            letter-spacing: 2px;
            cursor: default;
            pointer-events: none;
        }

        .page-link.ellipsis:hover {
            background: transparent;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .pagination {
                gap: 0.35rem;
            }

            .page-link {
                min-width: 38px;
                height: 38px;
                padding: 0 0.7rem;
                font-size: 0.9rem;
                border-radius: 12px;
            }

            .page-link.prev-next {
                min-width: 38px;
            }
        }

        @media (max-width: 480px) {
            .pagination {
                gap: 0.25rem;
            }

            .page-link {
                min-width: 36px;
                height: 36px;
                padding: 0 0.5rem;
                font-size: 0.85rem;
                border-radius: 10px;
            }
        }

        .page-link:focus-visible {
            outline: none;
            box-shadow: var(--pagination-glow);
            border-color: var(--accent-color);
        }

        .page-item.active .page-link {
            animation: pop 0.2s ease;
        }

        @keyframes pop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }

            100% {
                transform: scale(1) translateY(-2px);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        {{-- Header with Create Button --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">إدارة الفئات الغذائية لتنظيم قاعدة بيانات التغذية</p>
            </div>
            <a href="{{ route('food-category.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle ms-2"></i>فئة جديدة
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Categories Table Card --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 category-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>الاسم</th>
                                    <th>عدد العناصر الغذائية</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-end pe-4">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="fw-medium">{{ $category->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $category->food_items_count ?? $category->categories->count() }} عنصر
                                            </span>
                                        </td>
                                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('food-category.edit', $category) }}"
                                                class="btn btn-sm btn-outline-primary action-btn me-2" data-bs-toggle="tooltip"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                                onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
                                                data-bs-toggle="tooltip" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            {{-- Hidden delete form --}}
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('food-category.destroy', $category) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-utensils fa-4x mb-3" style="opacity: 0.5;"></i>
                        <h4>لا توجد فئات غذائية</h4>
                        <p class="mb-4">ابدأ بإنشاء أول فئة غذائية لك.</p>
                        <a href="{{ route('food-category.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle ms-2"></i>إنشاء فئة
                        </a>
                    </div>
                @endif
            </div>
            @if($categories->hasPages())
                <div class="pagination-container">
                    <nav role="navigation" aria-label="Pagination Navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link (Right Arrow for Arabic RTL) --}}
                            @if($categories->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link prev-next">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link prev-next" href="{{ $categories->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- First page with ellipsis logic --}}
                            @if($categories->currentPage() > 3)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->url(1) }}">1</a>
                                </li>
                                @if($categories->currentPage() > 4)
                                    <li class="page-item disabled">
                                        <span class="page-link ellipsis">•••</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Pages around current page --}}
                            @foreach(range(1, $categories->lastPage()) as $i)
                                @if($i >= $categories->currentPage() - 2 && $i <= $categories->currentPage() + 2)
                                    @if($i == $categories->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link active-page">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach

                            {{-- Last page with ellipsis logic --}}
                            @if($categories->currentPage() < $categories->lastPage() - 2)
                                @if($categories->currentPage() < $categories->lastPage() - 3)
                                    <li class="page-item disabled">
                                        <span class="page-link ellipsis">•••</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->url($categories->lastPage()) }}">
                                        {{ $categories->lastPage() }}
                                    </a>
                                </li>
                            @endif

                            {{-- Next Page Link (Left Arrow for Arabic RTL) --}}
                            @if($categories->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link prev-next" href="{{ $categories->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link prev-next">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // SweetAlert2 Delete Confirmation with Arabic text
        function confirmDelete(categoryId, categoryName) {
            Swal.fire({
                title: 'حذف الفئة؟',
                html: `هل أنت متأكد من حذف <strong>${categoryName}</strong>؟<br><small class="text-danger">لا يمكن التراجع عن هذا الإجراء.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذفها!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${categoryId}`).submit();
                }
            });
        }
    </script>
@endpush