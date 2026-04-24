<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BUKA BUKU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; }
        .container { display: flex; min-height: 100vh; }
        .sidebar {
            width: 260px; background: #0f172a; color: white;
            position: fixed; height: 100vh; overflow-y: auto; padding: 20px 0;
        }
        .sidebar-logo { padding: 0 20px 20px; font-size: 20px; font-weight: bold; border-bottom: 1px solid #1e293b; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu li { margin-bottom: 5px; }
        .sidebar-menu a {
            display: block; padding: 12px 20px; color: #cbd5e1; text-decoration: none;
            transition: 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #1e293b; color: #f97316; }
        .main-content { margin-left: 260px; flex: 1; padding: 30px; }
        .header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        .header h1 { font-size: 28px; color: #0f172a; }
        .logout-btn {
            background: #ef4444; color: white; border: none; padding: 10px 20px;
            border-radius: 8px; cursor: pointer; font-weight: 600;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        .stat-card {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .stat-card h3 { font-size: 14px; color: #64748b; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: 700; color: #0f172a; }
        .stat-card.total-books .number { color: #3b82f6; }
        .stat-card.total-users .number { color: #10b981; }
        .stat-card.active-loans .number { color: #f59e0b; }
        .stat-card.total-fines .number { color: #ef4444; }
        @media (max-width: 768px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">📚 BUKA BUKU</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active">🏠 Dashboard</a></li>
                <li><a href="{{ route('admin.books') }}">📚 Kelola Buku</a></li>
                <li><a href="{{ route('admin.transactions') }}">📋 Peminjaman</a></li>
                <li><a href="{{ route('admin.users.create') }}">👤 Daftar User</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Admin</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
            <div class="stats-grid">
                <div class="stat-card total-books">
                    <h3>Total Buku</h3>
                    <div class="number">{{ $totalBooks }}</div>
                </div>
                <div class="stat-card total-users">
                    <h3>Total User</h3>
                    <div class="number">{{ $totalUsers }}</div>
                </div>
                <div class="stat-card active-loans">
                    <h3>Peminjaman Aktif</h3>
                    <div class="number">{{ $activeTransactions }}</div>
                </div>
                <div class="stat-card total-fines">
                    <h3>Total Denda (Aktif)</h3>
                    <div class="number">Rp {{ number_format($totalFines, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

