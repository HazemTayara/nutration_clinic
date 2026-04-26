{{-- resources/views/food-items/index.blade.php --}}
@extends('layouts.app')

@section('title', 'مكتبة الأصناف الغذائية')
@section('page-title', 'مكتبة الأصناف الغذائية')

@push('styles')
    <style>
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .food-table th {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            white-space: nowrap;
        }

        .food-table td {
            vertical-align: middle;
        }

        .nutrition-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 2px;
        }

        .calories-badge {
            background: #FFF3E0;
            color: #E65100;
        }

        .protein-badge {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .carbs-badge {
            background: #E3F2FD;
            color: #1565C0;
        }

        .fat-badge {
            background: #FCE4EC;
            color: #C2185B;
        }

        .portion-badge {
            background: #E0F7FA;
            color: #006064;
        }

        .category-tag {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
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

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }

        .search-box input {
            padding-right: 45px;
            border-radius: 30px;
            border: 1px solid var(--light-gray);
        }

        .stats-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 16px 20px;
            color: white;
            margin-bottom: 20px;
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
                <p class="text-muted mb-0">إدارة قاعدة بيانات الأغذية مع معلومات غذائية مفصلة</p>
            </div>
            <div>
                <a href="{{ route('food-items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle ms-2"></i>إضافة صنف غذائي
                </a>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="stats-summary">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <i class="fas fa-database fa-2x ms-2"></i>
                    <span class="fw-bold">{{ $foodItems->total() }}</span> إجمالي الأصناف
                </div>
                <div class="col-md-3">
                    <i class="fas fa-tags fa-2x ms-2"></i>
                    <span class="fw-bold">{{ $categoriesCount ?? 0 }}</span> فئة
                </div>
                <div class="col-md-6 text-md-start">
                    <i class="fas fa-lightbulb ms-2"></i>
                    <small>نصيحة: استخدم الفلاتر للعثور على أطعمة محددة بسرعة</small>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('food-items.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" name="search" placeholder="ابحث بالاسم..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->food_items_count ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter ms-2"></i>تصفية
                            </button>
                            <a href="{{ route('food-items.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo-alt"></i>
                            </a>
                        </div>
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

        {{-- Food Items Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                @if($foodItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 food-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>الاسم</th>
                                    <th>الحصة</th>
                                    <th>الفئة</th>
                                    <th class="text-center">السعرات الحرارية</th>
                                    <th class="text-center">الكربوهيدرات</th>
                                    <th class="text-center">البروتين</th>
                                    <th class="text-center">الدهون</th>
                                    <th class="text-end pe-4">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($foodItems as $item)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $loop->iteration + $foodItems->firstItem() - 1 }}</td>
                                        <td>
                                            <span class="fw-medium">{{ $item->name }}</span>
                                        </td>
                                        <td>
                                            <span class="nutrition-badge portion-badge">
                                                {{ $item->portion_quantity }}
                                                {{ $item->unit->name}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="category-tag">
                                                <i class="fas fa-folder ms-1"></i>
                                                {{ $item->category->name ?? 'غير مصنف' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nutrition-badge calories-badge">
                                                {{ number_format($item->calories, 1) }} سعرة
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nutrition-badge carbs-badge">
                                                {{ number_format($item->carbs, 1) }}جم
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nutrition-badge protein-badge">
                                                {{ number_format($item->protein, 1) }}جم
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nutrition-badge fat-badge">
                                                {{ number_format($item->fat, 1) }}جم
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('food-items.show', $item) }}"
                                                class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="tooltip"
                                                title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('food-items.edit', $item) }}"
                                                class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="tooltip"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                                data-bs-toggle="tooltip" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('food-items.destroy', $item) }}"
                                                method="POST" style="display: none;">
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
                        <i class="fas fa-apple-alt fa-4x mb-3" style="opacity: 0.5;"></i>
                        <h4>لا توجد أصناف غذائية</h4>
                        <p class="mb-4">
                            @if(request()->hasAny(['search', 'category']))
                                لا توجد عناصر تطابق معايير البحث. حاول تعديل معايير البحث الخاصة بك.
                            @else
                                ابدأ في بناء قاعدة بيانات الأغذية الخاصة بك عن طريق إضافة العنصر الأول.
                            @endif
                        </p>
                        @if(!request()->hasAny(['search', 'category']))
                            <a href="{{ route('food-items.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle ms-2"></i>إضافة أول صنف غذائي
                            </a>
                        @else
                            <a href="{{ route('food-items.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-redo-alt ms-2"></i>مسح الفلاتر
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            @if($foodItems->hasPages())
                <div class="pagination-container">
                    <nav role="navigation" aria-label="التنقل بين الصفحات">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if($foodItems->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link prev-next">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link prev-next" href="{{ $foodItems->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- First page with ellipsis logic --}}
                            @if($foodItems->currentPage() > 3)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $foodItems->url(1) }}">1</a>
                                </li>
                                @if($foodItems->currentPage() > 4)
                                    <li class="page-item disabled">
                                        <span class="page-link ellipsis">•••</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Pages around current page --}}
                            @foreach(range(1, $foodItems->lastPage()) as $i)
                                @if($i >= $foodItems->currentPage() - 2 && $i <= $foodItems->currentPage() + 2)
                                    @if($i == $foodItems->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link active-page">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $foodItems->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach

                            {{-- Last page with ellipsis logic --}}
                            @if($foodItems->currentPage() < $foodItems->lastPage() - 2)
                                @if($foodItems->currentPage() < $foodItems->lastPage() - 3)
                                    <li class="page-item disabled">
                                        <span class="page-link ellipsis">•••</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $foodItems->url($foodItems->lastPage()) }}">
                                        {{ $foodItems->lastPage() }}
                                    </a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if($foodItems->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link prev-next" href="{{ $foodItems->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link prev-next">
                                        <i class="fas fa-chevron-right"></i>
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
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        function confirmDelete(itemId, itemName) {
            Swal.fire({
                title: 'حذف الصنف الغذائي؟',
                html: `هل أنت متأكد من رغبتك في حذف <strong>${itemName}</strong>؟<br>
                       <small class="text-danger">سيؤدي هذا أيضاً إلى إزالته من أي خطط وجبات.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${itemId}`).submit();
                }
            });
        }
    </script>
@endpush