<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman - BUKA BUKU</title>
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
        .header h1 { font-size: 28px; color: #0f172a; margin-bottom: 30px; }
        .section-title { font-size: 20px; color: #334155; margin: 30px 0 15px; }
        .table-container {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { color: #64748b; font-weight: 600; font-size: 14px; }
        td { color: #334155; }
        .badge {
            padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge-borrowed { background: #fef3c7; color: #d97706; }
        .badge-returned { background: #dcfce7; color: #166534; }
        .fine-warning { color: #ef4444; font-weight: 600; }
        .fine-safe { color: #10b981; }
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
                <li><a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('admin.books') }}">📚 Kelola Buku</a></li>
                <li><a href="{{ route('admin.transactions') }}" class="active">📋 Peminjaman</a></li>
                <li><a href="{{ route('admin.users.create') }}">👤 Daftar User</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Pemantauan Peminjaman</h1>
            </div>

            <div class="section-title">Peminjaman Aktif</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Keterlambatan</th>
                            <th>Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>{{ $transaction->book->title ?? '-' }}</td>
                            <td>{{ $transaction->borrow_date->format('d M Y') }}</td>
                            <td>{{ $transaction->due_date->format('d M Y') }}</td>
                            <td>
                                @if($transaction->late_days > 0)
                                    <span class="fine-warning">{{ $transaction->late_days }} hari</span>
                                @else
                                    <span class="fine-safe">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->fine_amount > 0)
                                    <span class="fine-warning">Rp {{ number_format($transaction->fine_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="fine-safe">Rp 0</span>
                                @endif
                            </td>
                            <td><span class="badge badge-borrowed">Dipinjam</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:#64748b;">Tidak ada peminjaman aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="section-title">Riwayat Pengembalian</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returnedTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>{{ $transaction->book->title ?? '-' }}</td>
                            <td>{{ $transaction->borrow_date->format('d M Y') }}</td>
                            <td>{{ $transaction->return_date ? $transaction->return_date->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $fine = 0;
                                    if ($transaction->return_date && $transaction->return_date->greaterThan($transaction->due_date)) {
                                        $lateDays = $transaction->return_date->diffInDays($transaction->due_date);
                                        $fine = $lateDays * 5000;
                                    }
                                @endphp
                                @if($fine > 0)
                                    <span class="fine-warning">Rp {{ number_format($fine, 0, ',', '.') }}</span>
                                @else
                                    <span class="fine-safe">Rp 0</span>
                                @endif
                            </td>
                            <td><span class="badge badge-returned">Dikembalikan</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:#64748b;">Belum ada riwayat pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

