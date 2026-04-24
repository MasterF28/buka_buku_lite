<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'BUKA BUKU')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f5f6fa; min-height: 100vh; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 260px; background: #fff; border-right: 1px solid #eee;
            position: fixed; left: 0; top: 0; height: 100vh; overflow-y: auto;
            padding: 20px; z-index: 1000; transition: transform .3s ease;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px; font-weight: bold; font-size: 18px; color: #333;
        }
        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin-bottom: 4px; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; color: #666; padding: 12px 10px;
            border-radius: 8px; transition: .2s; font-size: 14px; min-height: 44px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #f0f0f0; color: #e63946; font-weight: 600; }

        /* ========== OVERLAY ========== */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        /* ========== TOPBAR (mobile header) ========== */
        .topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0;
            height: 56px; background: #fff; color: #333;
            align-items: center; justify-content: space-between;
            padding: 0 16px; z-index: 998; border-bottom: 1px solid #eee;
        }
        .topbar .hamburger {
            width: 44px; height: 44px; display: flex; flex-direction: column;
            justify-content: center; align-items: center; gap: 5px;
            background: transparent; border: none; cursor: pointer;
        }
        .topbar .hamburger span {
            display: block; width: 24px; height: 2.5px; background: #333;
            border-radius: 2px; transition: .2s;
        }
        .topbar-title { font-weight: 700; font-size: 16px; color: #e63946; }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }
        .topbar-avatar {
            width: 34px; height: 34px; border-radius: 50%; background: #e63946;
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 14px;
        }
        .topbar-logout {
            background: #e63946; color: white; border: none; padding: 6px 12px;
            border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content { margin-left: 260px; flex: 1; padding: 30px; min-height: 100vh; }

        /* ========== HEADER ========== */
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
            background: white; padding: 20px; border-radius: 12px;
        }
        .page-header h1 { font-size: 22px; color: #333; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%; background: #e63946;
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }
        .logout-btn {
            background: #e63946; color: white; border: none;
            padding: 8px 16px; border-radius: 6px; cursor: pointer;
            font-size: 14px; font-weight: 600; transition: .2s; min-height: 36px;
        }
        .logout-btn:hover { background: #d62828; }

        /* ========== FILTER ========== */
        .filter-section {
            background: white; padding: 16px 20px; border-radius: 12px;
            margin-bottom: 24px; display: flex; gap: 12px;
            flex-wrap: wrap; align-items: center;
        }
        .filter-section label { font-weight: 600; color: #333; font-size: 14px; }
        .filter-section select, .filter-section input {
            padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px;
            font-size: 14px; outline: none; min-height: 40px; font-family: inherit;
        }
        .filter-section input { flex: 1; min-width: 180px; max-width: 320px; }

        /* ========== BOOKS GRID ========== */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }
        .book-card {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: .2s; cursor: pointer;
            display: flex; flex-direction: column;
        }
        .book-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
        .book-image {
            width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 12px; text-align: center; padding: 10px; font-weight: bold;
            overflow: hidden; position: relative;
        }
        .book-image img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
        .book-info { padding: 14px; flex: 1; display: flex; flex-direction: column; }
        .book-category {
            display: inline-block; background: #e63946; color: white;
            padding: 3px 10px; border-radius: 20px; font-size: 11px;
            margin-bottom: 8px; width: fit-content;
        }
        .book-title {
            font-size: 14px; font-weight: 600; color: #333; margin-bottom: 4px;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .book-author {
            font-size: 12px; color: #999; margin-bottom: 10px;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .book-stock { font-size: 12px; color: #666; margin-bottom: 12px; margin-top: auto; }
        .select-btn {
            width: 100%; background: #e63946; color: white; border: none;
            padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 600;
            font-size: 13px; transition: .2s; min-height: 40px;
        }
        .select-btn:hover { background: #d62828; }
        .select-btn:disabled { background: #ccc; cursor: not-allowed; }
        .select-btn.return { background: #16a34a; }
        .select-btn.return:hover { background: #15803d; }

        /* ========== HISTORY / BORROW CARDS ========== */
        .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }
        .history-card {
            background: white; border-radius: 14px; padding: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
            display: flex; gap: 14px; align-items: flex-start; transition: .2s;
        }
        .history-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.1); }
        .history-thumb {
            width: 64px; height: 90px; border-radius: 8px; overflow: hidden;
            flex-shrink: 0; background: #e2e8f0;
        }
        .history-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .history-thumb-placeholder {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            font-size: 10px; color: #64748b; text-align: center; padding: 4px;
        }
        .history-body { flex: 1; min-width: 0; }
        .history-body .title { font-size: 15px; font-weight: 600; color: #0f172a; margin-bottom: 3px; }
        .history-body .author { color: #64748b; font-size: 13px; margin-bottom: 10px; }
        .history-meta { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; font-size: 13px; }
        .history-meta .label { color: #64748b; }
        .history-meta .value { color: #0f172a; font-weight: 600; }
        .badge-status {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; border-radius: 20px; font-size: 12px;
            font-weight: 600; margin-bottom: 10px;
        }
        .badge-status.safe { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; }
        .badge-status.late { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .fine-text { font-size: 13px; color: #dc2626; font-weight: 700; margin-bottom: 10px; }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            padding: 40px; background: #fff; border-radius: 14px;
            text-align: center; color: #64748b; box-shadow: 0 4px 12px rgba(0,0,0,.05);
        }
        .empty-state .icon { font-size: 40px; margin-bottom: 10px; }

        /* ========== NO BOOKS ========== */
        .no-books { grid-column: 1 / -1; text-align: center; padding: 40px; color: #999; }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); box-shadow: 4px 0 20px rgba(0,0,0,.1); }
            .sidebar.open { transform: translateX(0); }
            .topbar { display: flex; }
            .main-content { margin-left: 0; padding: 72px 12px 20px; }
            .page-header { padding: 14px 16px; border-radius: 10px; }
            .page-header h1 { font-size: 18px; }
            .user-info { display: none; }
            .filter-section { padding: 12px 14px; gap: 8px; }
            .filter-section input { max-width: 100%; min-width: 140px; }
            .books-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .book-image { aspect-ratio: 3/4; }
            .book-info { padding: 10px; }
            .book-title { font-size: 13px; }
            .history-grid { grid-template-columns: 1fr; }
            .history-card { gap: 12px; padding: 14px; }
        }
        @media (max-width: 360px) {
            .books-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <div class="topbar-title">📚 BUKA BUKU</div>
        <div class="topbar-actions">
            <div class="topbar-avatar">{{ substr(session('nim','?'), 0, 1) }}</div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="topbar-logout">Logout</button>
            </form>
        </div>
    </div>

    <aside class="sidebar" id="userSidebar">
        <div class="sidebar-logo">📚 BUKA BUKU</div>
        <ul class="sidebar-menu">
            @php
                $path = request()->path();
            @endphp
            <li><a href="/books" class="{{ $path == 'books' ? 'active' : '' }}">🏠 Beranda</a></li>
            <li><a href="#riwayat" class="{{ str_contains($path, 'riwayat') ? 'active' : '' }}">📋 Riwayat Peminjaman</a></li>
        </ul>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('userSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        document.querySelectorAll('.sidebar-menu a').forEach(a => {
            a.addEventListener('click', () => {
                if (window.innerWidth <= 768) toggleSidebar();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

