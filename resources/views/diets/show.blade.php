@extends('layouts.app')

@section('title', 'تفاصيل الخطة الغذائية')
@section('page-title', 'الخطة الغذائية: ' . $diet->patient->name)

@push('styles')
    {{-- Tom Select (searchable dropdown, no jQuery needed) --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .diet-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            border-radius: 24px;
            padding: 24px 30px;
            color: white;
            margin-bottom: 24px;
        }

        .day-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 16px;
        }

        .day-tab {
            padding: 10px 20px;
            border-radius: 30px;
            background: white;
            color: var(--gray-text);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 1px solid var(--light-gray);
            cursor: pointer;
            text-align: center;
        }

        .day-tab:hover,
        .day-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .day-content { display: none; }
        .day-content.active { display: block; }

        .meal-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .meal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .meal-type-badge {
            padding: 6px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
        }

        .meal-type-breakfast { background: #FFF3E0; color: #E65100; }
        .meal-type-lunch     { background: #E8F5E9; color: #2E7D32; }
        .meal-type-dinner    { background: #E3F2FD; color: #1565C0; }
        .meal-type-snack     { background: #F3E5F5; color: #6A1B9A; }

        .food-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e0e0e0;
        }

        .nutrition-summary {
            background: #F8F9FA;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 16px;
        }

        .add-item-form {
            background: #F8F9FA;
            border-radius: 16px;
            padding: 16px;
            margin-top: 16px;
        }

        .target-progress {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 20px;
        }

        /* Make TomSelect look like a Bootstrap form-select */
        .ts-wrapper.form-select { padding: 0; }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        {{-- Header --}}
        <div class="diet-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $diet->patient->name }}</h2>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-calendar-alt ms-2"></i>
                        {{ $diet->start_date->format('M d, Y') }} - {{ $diet->end_date->format('M d, Y') }}
                        <span class="mx-3">•</span>
                        <i class="fas fa-chart-line ms-2"></i>
                        الهدف: {{ round($targets['calories']) }} سعرة
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <form action="{{ route('diets.destroy', $diet) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الخطة الغذائية؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light">
                            <i class="fas fa-trash-alt ms-2"></i>حذف الخطة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @php
            $firstDay = $diet->dietDays->first();
        @endphp

        @if($firstDay)
            {{-- Day Tabs --}}
            <div class="day-tabs" id="dayTabs">
                @foreach($diet->dietDays as $day)
                    <button type="button"
                            class="day-tab {{ $day->id == $firstDay->id ? 'active' : '' }}"
                            data-day-id="{{ $day->id }}"
                            data-meal-store-url="{{ route('diets.days.meals.store', [$diet, $day]) }}">
                        اليوم {{ $loop->iteration }}<br>
                        <small>{{ $day->date->format('M d') }}</small>
                    </button>
                @endforeach
            </div>

            {{-- All Day Contents (only active is visible) --}}
            <div id="dayContents">
                @foreach($diet->dietDays as $day)
                    <div class="day-content {{ $day->id == $firstDay->id ? 'active' : '' }}"
                         data-day-id="{{ $day->id }}">
                        @include('diets._day', [
                            'day'       => $day,
                            'diet'      => $diet,
                            'targets'   => $targets,
                            'foodItems' => $foodItems,
                        ])
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center py-5">لا توجد أيام متاحة.</p>
        @endif
    </div>

    {{-- Add Meal Modal (single, shared across days) --}}
    <div class="modal fade" id="addMealModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addMealForm" method="POST" data-ajax-form>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة وجبة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">نوع الوجبة</label>
                            <select name="type" class="form-select" required>
                                <option value="">اختر النوع</option>
                                @foreach(\App\Models\Meal::TYPES as $type)
                                    <option value="{{ $type }}">
                                        @switch($type)
                                            @case('breakfast') إفطار @break
                                            @case('lunch') غداء @break
                                            @case('dinner') عشاء @break
                                            @case('snack') وجبة خفيفة @break
                                            @default {{ ucfirst($type) }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">الحد الأقصى 3 وجبات خفيفة في اليوم.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة الوجبة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        (function () {
            const dayTabsEl     = document.getElementById('dayTabs');
            const dayContentsEl = document.getElementById('dayContents');
            const addMealModal  = document.getElementById('addMealModal');
            const addMealForm   = document.getElementById('addMealForm');
            const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            let activeDayId = @json($firstDay?->id);

            /* ---------- Tom Select (searchable food dropdowns) ---------- */
            function initFoodSelects(root) {
                (root || document).querySelectorAll('select.food-item-select').forEach(sel => {
                    if (sel.tomselect) return;
                    new TomSelect(sel, {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'ابحث عن الطعام بالاسم...',
                        maxOptions: 1000,
                    });
                });
            }

            /* ---------- Day tab switching (no refresh) ---------- */
            function showDay(dayId) {
                if (!dayId) return;
                activeDayId = dayId;

                dayTabsEl.querySelectorAll('.day-tab').forEach(t => {
                    t.classList.toggle('active', String(t.dataset.dayId) === String(dayId));
                });
                dayContentsEl.querySelectorAll('.day-content').forEach(c => {
                    c.classList.toggle('active', String(c.dataset.dayId) === String(dayId));
                });
            }

            if (dayTabsEl) {
                dayTabsEl.addEventListener('click', function (e) {
                    const tab = e.target.closest('.day-tab');
                    if (!tab) return;
                    showDay(tab.dataset.dayId);
                });
            }

            /* ---------- Open Add-Meal modal bound to active day ---------- */
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-action="open-add-meal"]');
                if (!btn) return;
                const activeTab = dayTabsEl?.querySelector(`.day-tab[data-day-id="${activeDayId}"]`);
                if (!activeTab || !addMealForm) return;
                addMealForm.setAttribute('action', activeTab.dataset.mealStoreUrl);
                addMealForm.reset();
            });

            /* ---------- Generic AJAX form handler ---------- */
            async function handleAjaxForm(form) {
                const fd = new FormData(form);

                try {
                    const resp = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    let data = null;
                    try { data = await resp.json(); } catch (_) { /* ignore */ }

                    if (!resp.ok || !data || data.success === false) {
                        const msg = data?.message || 'حدث خطأ ما. يرجى المحاولة مرة أخرى.';
                        showToast(msg, 'error');
                        return;
                    }

                    // Swap in the updated day HTML
                    if (data.day_id && data.html) {
                        const container = dayContentsEl.querySelector(
                            `.day-content[data-day-id="${data.day_id}"]`
                        );
                        if (container) {
                            container.innerHTML = data.html;
                            initFoodSelects(container);
                        }
                    }

                    // Close modal if this was the add-meal form
                    if (form.id === 'addMealForm' && addMealModal) {
                        bootstrap.Modal.getInstance(addMealModal)?.hide();
                        form.reset();
                    }

                    if (data.message) showToast(data.message, 'success');
                } catch (err) {
                    showToast('خطأ في الشبكة. يرجى المحاولة مرة أخرى.', 'error');
                }
            }

            function showToast(message, type) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false,
                        icon: type === 'error' ? 'error' : 'success',
                        title: message,
                    });
                }
            }

            /* ---------- Intercept all AJAX forms ---------- */
            document.addEventListener('submit', function (e) {
                const form = e.target.closest('form[data-ajax-form]');
                if (!form) return;
                e.preventDefault();

                const confirmMsg = form.dataset.confirm;
                if (confirmMsg && !window.confirm(confirmMsg)) return;

                handleAjaxForm(form);
            });

            /* ---------- Initial setup ---------- */
            initFoodSelects();
        })();
    </script>
@endpush