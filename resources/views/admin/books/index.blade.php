<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku - BUKA BUKU</title>
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
        .btn-add {
            background: #10b981; color: white; text-decoration: none;
            padding: 12px 20px; border-radius: 8px; font-weight: 600;
        }
        .table-container {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { color: #64748b; font-weight: 600; font-size: 14px; }
        td { color: #334155; }
        .book-img {
            width: 50px; height: 70px; object-fit: cover; border-radius: 4px;
            background: #e2e8f0;
        }
        .btn-edit {
            background: #3b82f6; color: white; text-decoration: none;
            padding: 6px 12px; border-radius: 6px; font-size: 13px; margin-right: 5px;
        }
        .btn-delete {
            background: #ef4444; color: white; border: none;
            padding: 6px 12px; border-radius: 6px; font-size: 13px; cursor: pointer;
        }
        .success-msg {
            background: #dcfce7; color: #166534; padding: 12px 16px;
            border-radius: 8px; margin-bottom: 20px;
        }
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
                <li><a href="{{ route('admin.books') }}" class="active">📚 Kelola Buku</a></li>
                <li><a href="{{ route('admin.transactions') }}">📋 Peminjaman</a></li>
                <li><a href="{{ route('admin.users.create') }}">👤 Daftar User</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Kelola Buku</h1>
                <a href="{{ route('admin.books.create') }}" class="btn-add">+ Tambah Buku</a>
            </div>
            @if(session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td>
                                @if($book->image_url)
                                    <img src="{{ $book->image_url }}" class="book-img" alt="{{ $book->title }}">
                                @else
                                    <div class="book-img" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:#64748b;">No Image</div>
                                @endif
                            </td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->category->name ?? '-' }}</td>
                            <td>{{ $book->stock }}</td>
                            <td>
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:#64748b;">Belum ada data buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

