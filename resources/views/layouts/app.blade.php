<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    {{-- Modern Arabic Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    {{-- Icons & Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>@yield('title', 'لوحة تحكم عيادة التغذية')</title>
    <style>
        :root {
            /* Fresh Nutrition Palette */
            --primary: #2E7D32;
            /* Deep Green */
            --primary-light: #81C784;
            /* Soft Green */
            --primary-dark: #1B5E20;
            /* Dark Forest */
            --accent: #FFA000;
            /* Amber for highlights */
            --bg-light: #F8F9FA;
            --white: #FFFFFF;
            --gray-text: #4A5568;
            --light-gray: #E2E8F0;
            --shadow-sm: 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --transition: all 0.25s ease;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Tajawal', 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--gray-text);
            font-weight: 400;
            direction: rtl;
            text-align: right;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* ---------- SIDEBAR (Right side for RTL) ---------- */
        .sidebar {
            width: 260px;
            background: linear-gradient(165deg, var(--primary-dark) 0%, var(--primary) 100%);
            padding: 24px 16px;
            color: white;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 8px 0 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .sidebar-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin: 0;
            color: white;
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-nav a,
        .sidebar-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 12px;
            transition: var(--transition);
            font-size: 15px;
            font-weight: 500;
        }

        .sidebar-nav a i,
        .sidebar-dropdown-toggle i {
            width: 22px;
            font-size: 1.2rem;
            text-align: center;
        }

        .sidebar-nav a:hover,
        .sidebar-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(-4px);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-right: 4px solid white;
            border-radius: 4px 12px 12px 4px;
        }

        /* Dropdown Styles (RTL) */
        .sidebar-dropdown-container {
            position: relative;
        }

        .sidebar-dropdown-toggle {
            justify-content: space-between;
            cursor: pointer;
        }

        .sidebar-dropdown-toggle.active {
            background: rgba(255, 255, 255, 0.18);
            font-weight: 600;
            border-right: 4px solid white;
            border-radius: 4px 12px 12px 4px;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s;
            margin-right: auto;
        }

        .show .dropdown-arrow {
            transform: rotate(180deg);
        }

        .sidebar-dropdown {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            margin: 6px 8px 6px 0;
            padding: 8px 0;
            width: calc(100% - 8px);
            display: none;
            backdrop-filter: blur(8px);
            border-right: 2px solid var(--primary-light);
        }

        .sidebar-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar-dropdown a {
            padding: 10px 40px 10px 16px;
            font-size: 14px;
            font-weight: 400;
            border-radius: 8px;
        }

        .sidebar-dropdown a.active {
            background: rgba(255, 255, 255, 0.25);
            border-right: 4px solid white;
            padding-right: 36px;
        }

        /* ---------- MAIN CONTENT (Offset for right sidebar) ---------- */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-right: 260px;
            width: calc(100% - 260px);
            transition: var(--transition);
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 32px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--light-gray);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-title {
            color: var(--primary-dark);
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .user-dropdown .btn-light {
            background: white;
            border: 1px solid var(--light-gray);
            border-radius: 40px;
            padding: 8px 16px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        /* Content Area */
        .content-area {
            padding: 32px;
            flex: 1;
            background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%);
            overflow-y: auto;
            min-height: calc(100vh - 70px);
        }

        /* Modern Widget Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            border: none;
            border-radius: 24px;
            background: var(--white);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            padding: 24px 20px;
            border-right: 6px solid var(--primary);
            display: flex;
            flex-direction: column;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-right-color: var(--accent);
        }

        .stat-card h3 {
            margin: 0 0 12px 0;
            color: #64748B;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stat-card p {
            font-size: 36px;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 8px;
        }

        .stat-card .change {
            font-size: 13px;
            font-weight: 500;
            color: #10B981;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .change.negative {
            color: #EF4444;
        }

        /* Activity & Quick Actions */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            margin: 0;
            color: var(--primary-dark);
            font-size: 22px;
            font-weight: 700;
        }

        .activity-container {
            background: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 32px;
        }

        .activity-item {
            display: flex;
            padding: 16px 0;
            border-bottom: 1px solid #EDF2F7;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, var(--primary-light), var(--primary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 16px;
            color: white;
            font-size: 18px;
        }

        .activity-details {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 6px;
        }

        .activity-time {
            color: #94A3B8;
            font-size: 13px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .action-card {
            background: white;
            border-radius: 20px;
            padding: 28px 16px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .action-card:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .action-card i {
            font-size: 36px;
            color: var(--primary-dark);
            margin-bottom: 16px;
            display: block;
        }

        .action-card span {
            font-weight: 600;
            color: #1E293B;
            font-size: 16px;
        }

        /* Mobile Menu Toggle (RTL) */
        .menu-toggle {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
            position: fixed;
            right: 20px;
            top: 20px;
            z-index: 1001;
            box-shadow: var(--shadow-md);
        }

        /* Responsive */
        @media (max-width: 1200px) {

            .stats-container,
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 240px;
                padding: 20px 12px;
            }

            .main-content {
                margin-right: 240px;
                width: calc(100% - 240px);
            }
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(100%);
                width: 280px;
                z-index: 1050;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
            }

            .main-content {
                margin-right: 0;
                width: 100%;
            }

            .stats-container,
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .content-area {
                padding: 20px;
            }

            .top-bar {
                padding: 16px 20px;
            }
        }

        /* Buttons & Overrides */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .pagination .page-link {
            color: var(--primary);
        }

        .form-switch .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Custom Scroll */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        /* Additional RTL fixes */
        .ms-1,
        .ms-2,
        .ms-3,
        .ms-4,
        .ms-5 {
            margin-left: unset !important;
        }

        .ms-1 {
            margin-right: 0.25rem !important;
        }

        .ms-2 {
            margin-right: 0.5rem !important;
        }

        .ms-3 {
            margin-right: 1rem !important;
        }

        .ms-4 {
            margin-right: 1.5rem !important;
        }

        .ms-5 {
            margin-right: 3rem !important;
        }

        .me-1,
        .me-2,
        .me-3,
        .me-4,
        .me-5 {
            margin-right: unset !important;
        }

        .me-1 {
            margin-left: 0.25rem !important;
        }

        .me-2 {
            margin-left: 0.5rem !important;
        }

        .me-3 {
            margin-left: 1rem !important;
        }

        .me-4 {
            margin-left: 1.5rem !important;
        }

        .me-5 {
            margin-left: 3rem !important;
        }

        .dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard-container">
        {{-- Sidebar (Right) --}}
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="شعار العيادة" class="sidebar-logo"
                    onerror="this.style.display='none'">
                <div class="sidebar-title">عيادة كيوريتور</div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>لوحة التحكم</span>
                </a>

                <a href="{{ route('food-category.index') }}"
                    class="{{ request()->routeIs('food-category.*') ? 'active' : '' }}">
                    <i class="fas fa-utensils"></i>
                    <span>فئات الأطعمة</span>
                </a>

                <a href="{{ route('food-items.index') }}"
                    class="{{ request()->routeIs('food-items.*') ? 'active' : '' }}">
                    <i class="fas fa-apple-alt"></i>
                    <span>الأطعمة</span>
                </a>

                {{-- Patients Dropdown --}}
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-dropdown-toggle {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                        onclick="toggleDropdown(event, this)">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <i class="fas fa-users"></i>
                            <span>المرضى</span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="sidebar-dropdown {{ request()->routeIs('patients.*') ? 'show' : '' }}">
                        <a href="{{ route('patients.index') }}"
                            class="{{ request()->routeIs('patients.index') ? 'active' : '' }}">
                            <i class="fas fa-list-ul"></i> جميع المرضى
                        </a>
                        <a href="{{ route('patients.create') }}"
                            class="{{ request()->routeIs('patients.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i> مريض جديد
                        </a>
                    </div>
                </div>

                {{-- Meal Plans --}}
                {{-- <div class="sidebar-dropdown-container">
                    <div class="sidebar-dropdown-toggle" onclick="toggleDropdown(event, this)">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <i class="fas fa-utensils"></i>
                            <span>خطط الوجبات</span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="sidebar-dropdown">
                        <a href="#"><i class="fas fa-clipboard-list"></i> مكتبة الخطط</a>
                        <a href="#"><i class="fas fa-apple-alt"></i> إنشاء خطة</a>
                        <a href="#"><i class="fas fa-chart-bar"></i> تحليل غذائي</a>
                    </div>
                </div> --}}

                {{-- Recipes --}}
                {{-- <a href="#">
                    <i class="fas fa-book-open"></i>
                    <span>الوصفات</span>
                </a> --}}

                {{-- Reports --}}
                {{-- <a href="#">
                    <i class="fas fa-file-alt"></i>
                    <span>التقارير</span>
                </a> --}}

                {{-- Settings --}}
                {{-- <a href="#">
                    <i class="fas fa-sliders-h"></i>
                    <span>الإعدادات</span>
                </a> --}}

                <a href="{{ route('units.index') }}" class="{{ request()->routeIs('units.*') ? 'active' : '' }}">
                    <i class="fas fa-weight-scale"></i>
                    <span>الوحدات</span>
                </a>
            </nav>
            <div style="margin-top: auto; padding-top: 20px;">
                <div style="background: rgba(0,0,0,0.1); border-radius: 16px; padding: 16px;">
                    <small style="opacity:0.9;"><i class="fas fa-leaf ms-2"></i>العادات الصحية تبدأ من هنا</small>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">@yield('page-title', 'نظرة عامة على العيادة')</h1>
                <div class="dropdown user-dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-user-md me-2" style="color: var(--primary);"></i> د.
                        {{ Auth::user()->name ?? 'سميث' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href=""><i class="fas fa-user-circle me-2"></i>الملف الشخصي</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i
                                        class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content-area">
                {{-- Sample Dashboard Content (can be removed when extending) --}}
                @hasSection('content')
                    @yield('content')
                @else
                    {{-- Demo / Placeholder for smooth preview --}}
                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>إجمالي المرضى</h3>
                            <p>248</p>
                            <span class="change"><i class="fas fa-arrow-up"></i> +12 هذا الشهر</span>
                        </div>
                        <div class="stat-card">
                            <h3>مواعيد اليوم</h3>
                            <p>8</p>
                            <span class="change"><i class="fas fa-clock"></i> متبقي 2</span>
                        </div>
                        <div class="stat-card">
                            <h3>خطط الوجبات النشطة</h3>
                            <p>32</p>
                            <span class="change"><i class="fas fa-arrow-up"></i> +4 جديدة</span>
                        </div>
                        <div class="stat-card">
                            <h3>متوسط التقدم</h3>
                            <p>86%</p>
                            <span class="change negative"><i class="fas fa-arrow-down"></i> -2% عن الأسبوع الماضي</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="activity-container">
                                <div class="section-header">
                                    <h2><i class="fas fa-history me-2" style="color: var(--primary);"></i>آخر النشاطات</h2>
                                    <a href="#" class="text-decoration-none" style="color: var(--primary);">عرض الكل <i
                                            class="fas fa-arrow-left ms-1"></i></a>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-title">تسجيل مريضة جديدة: سارة جونسون</div>
                                        <div class="activity-time">قبل 10 دقائق</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fas fa-calendar-check"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-title">تأكيد موعد مع مايكل تشن</div>
                                        <div class="activity-time">قبل ساعة</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fas fa-utensils"></i></div>
                                    <div class="activity-details">
                                        <div class="activity-title">تحديث خطة وجبات "أسبوع البحر المتوسط"</div>
                                        <div class="activity-time">قبل 3 ساعات</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="activity-container h-100">
                                <div class="section-header">
                                    <h2>ملاحظات سريعة</h2>
                                </div>
                                <p style="color:#64748B;"><i class="far fa-sticky-note me-2"></i> مكالمة متابعة مع السيدة
                                    ديفيس الساعة 3 مساءً.</p>
                                <p style="color:#64748B;"><i class="far fa-clock me-2"></i> أفكار وصفات جديدة للنظام الغذائي
                                    الخالي من الجلوتين.</p>
                                <hr>
                                <small class="text-muted">آخر تحديث: اليوم، 09:23 صباحاً</small>
                            </div>
                        </div>
                    </div>

                    <div class="section-header mt-4">
                        <h2>إجراءات سريعة</h2>
                    </div>
                    <div class="quick-actions">
                        <div class="action-card" onclick="location.href='{{ route('patients.create') }}'">
                            <i class="fas fa-user-plus"></i>
                            <span>إضافة مريض</span>
                        </div>
                        <div class="action-card">
                            <i class="fas fa-calendar-plus"></i>
                            <span>موعد جديد</span>
                        </div>
                        <div class="action-card">
                            <i class="fas fa-utensil-spoon"></i>
                            <span>إنشاء خطة وجبات</span>
                        </div>
                        <div class="action-card">
                            <i class="fas fa-file-export"></i>
                            <span>إنشاء تقرير</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Dropdown toggle (RTL compatible)
        function toggleDropdown(event, element) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = element.nextElementSibling;
            const container = element.closest('.sidebar-dropdown-container');

            document.querySelectorAll('.sidebar-dropdown').forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });

            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                container.classList.add('active');
            } else {
                container.classList.remove('active');
            }
        }

        // Keep dropdown open if sublink active
        document.querySelectorAll('.sidebar-dropdown a').forEach(item => {
            if (item.classList.contains('active')) {
                const dropdown = item.closest('.sidebar-dropdown');
                if (dropdown) {
                    dropdown.classList.add('show');
                    dropdown.previousElementSibling?.classList.add('active');
                }
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.sidebar-dropdown-container')) {
                document.querySelectorAll('.sidebar-dropdown').forEach(d => d.classList.remove('show'));
            }
        });

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }

        document.addEventListener('click', function (e) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target) && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // Auto-adjust on resize
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar')?.classList.remove('active');
            }
        });

        // SweetAlert2 RTL configuration if needed
        if (typeof Swal !== 'undefined') {
            Swal.mixin({
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء',
                denyButtonText: 'لا',
                showCancelButton: true,
            });
        }
    </script>
    @stack('scripts')
</body>

</html>