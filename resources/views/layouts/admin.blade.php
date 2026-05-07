<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin - BUKA BUKU')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f1f5f9; min-height: 100vh; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 260px; background: #0f172a; color: white;
            position: fixed; left: 0; top: 0; height: 100vh; overflow-y: auto;
            padding: 20px 0; z-index: 1000;
            transition: transform .3s ease;
        }
        .sidebar-logo {
            padding: 0 20px 20px; font-size: 18px; font-weight: bold;
            border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 8px;
        }
        .sidebar-menu { list-style: none; padding: 16px 0; }
        .sidebar-menu li { margin-bottom: 4px; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 20px; color: #cbd5e1; text-decoration: none;
            transition: .2s; font-size: 14px; border-radius: 0 8px 8px 0;
            margin-right: 8px; min-height: 44px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #1e293b; color: #f97316; }

        /* ========== OVERLAY ========== */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        /* ========== TOPBAR (mobile header) ========== */
        .topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0;
            height: 56px; background: #0f172a; color: white;
            align-items: center; justify-content: space-between;
            padding: 0 16px; z-index: 998;
        }
        .topbar .hamburger {
            width: 44px; height: 44px; display: flex; flex-direction: column;
            justify-content: center; align-items: center; gap: 5px;
            background: transparent; border: none; cursor: pointer;
        }
        .topbar .hamburger span {
            display: block; width: 24px; height: 2px; background: white;
            border-radius: 2px; transition: .2s;
        }
        .topbar-title { font-weight: 600; font-size: 16px; }

        /* ========== MAIN CONTENT ========== */
        .main-content { margin-left: 260px; padding: 30px; min-height: 100vh; }
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
        }
        .page-header h1 { font-size: 26px; color: #0f172a; }

        /* ========== BUTTONS ========== */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 6px; padding: 10px 18px; border-radius: 8px; font-size: 14px;
            font-weight: 600; cursor: pointer; border: none; text-decoration: none;
            min-height: 44px; transition: .2s;
        }
        .btn-add { background: #10b981; color: white; }
        .btn-add:hover { background: #059669; }
        .btn-submit { background: #10b981; color: white; }
        .btn-submit:hover { background: #059669; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
        .btn-back { background: #e2e8f0; color: #334155; }
        .btn-back:hover { background: #cbd5e1; }
        .btn-logout { background: #ef4444; color: white; }
        .btn-logout:hover { background: #dc2626; }

        /* ========== CARD / TABLE ========== */
        .card {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1); margin-bottom: 20px;
        }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 640px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { color: #64748b; font-weight: 600; white-space: nowrap; }
        td { color: #334155; }
        tr:hover td { background: #f8fafc; }

        /* ========== FORM ========== */
        .form-card { max-width: 640px; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px; }
        .field input, .field select, .field textarea {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 10px;
            padding: 12px 14px; font-size: 15px; outline: none;
            transition: border-color .2s; min-height: 44px; font-family: inherit;
        }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: #f97316; }
        .field textarea { min-height: 120px; resize: vertical; }
        .error { color: #ef4444; font-size: 13px; margin-top: 5px; }
        .success-msg {
            background: #dcfce7; color: #166534; padding: 12px 16px;
            border-radius: 8px; margin-bottom: 20px; font-size: 14px;
        }

        /* ========== BADGES ========== */
        .badge {
            display: inline-block; padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 600; white-space: nowrap;
        }
        .badge-borrowed { background: #fef3c7; color: #d97706; }
        .badge-returned { background: #dcfce7; color: #166534; }
        .fine-warning { color: #ef4444; font-weight: 600; }
        .fine-safe { color: #10b981; }

        /* ========== STATS GRID ========== */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px; margin-bottom: 24px;
        }
        .stat-card {
            background: white; border-radius: 16px; padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
        }
        .stat-card h3 { font-size: 13px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .3px; }
        .stat-card .number { font-size: 28px; font-weight: 700; color: #0f172a; }
        .stat-card .number.blue { color: #3b82f6; }
        .stat-card .number.green { color: #10b981; }
        .stat-card .number.amber { color: #f59e0b; }
        .stat-card .number.red { color: #ef4444; }

        /* ========== BOOK IMAGE ========== */
        .book-img {
            width: 48px; height: 68px; object-fit: cover; border-radius: 4px;
            background: #e2e8f0; display: block;
        }
        .book-img-placeholder {
            width: 48px; height: 68px; border-radius: 4px; background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; color: #64748b; text-align: center;
        }
        .current-img { max-width: 180px; border-radius: 8px; margin-top: 8px; display: block; }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar { display: flex; }
            .main-content { margin-left: 0; padding: 76px 16px 24px; }
            .page-header h1 { font-size: 20px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .card { padding: 16px; border-radius: 12px; }
            th, td { padding: 10px; }
            .btn { padding: 10px 14px; font-size: 13px; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .form-card { max-width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Mobile Topbar -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <div class="topbar-title">📚 BUKA BUKU</div>
        <div style="width:44px;"></div>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-logo">📚 BUKA BUKU</div>
        <ul class="sidebar-menu">
            @php
                $route = Route::currentRouteName();
            @endphp
            <li><a href="{{ route('admin.dashboard') }}" class="{{ $route == 'admin.dashboard' ? 'active' : '' }}">🏠 Dashboard</a></li>
            <li><a href="{{ route('admin.memberships') }}" class="{{ $route == 'admin.memberships' ? 'active' : '' }}"> Memberships</a></li>
            <li><a href="{{ route('admin.books') }}" class="{{ in_array($route, ['admin.books','admin.books.create','admin.books.edit']) ? 'active' : '' }}">📚 Kelola Buku</a></li>
            <li><a href="{{ route('admin.transactions') }}" class="{{ $route == 'admin.transactions' ? 'active' : '' }}">📋 Peminjaman</a></li>
            <li><a href="{{ route('admin.users.create') }}" class="{{ in_array($route, ['admin.users.create','admin.users.store']) ? 'active' : '' }}">👤 Daftar User</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        // Close sidebar when clicking a link (mobile)
        document.querySelectorAll('.sidebar-menu a').forEach(a => {
            a.addEventListener('click', () => {
                if (window.innerWidth <= 768) toggleSidebar();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

