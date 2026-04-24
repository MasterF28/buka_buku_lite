<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - BUKA BUKU</title>
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
        .form-card {
            background: white; border-radius: 16px; padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-width: 600px;
        }
        .field { margin-bottom: 20px; }
        .field label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; }
        .field input, .field select, .field textarea {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 10px;
            padding: 12px; font-size: 15px; outline: none;
        }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: #f97316; }
        .field textarea { min-height: 120px; resize: vertical; }
        .btn-submit {
            background: #10b981; color: white; border: none;
            padding: 14px 24px; border-radius: 10px; font-size: 16px;
            font-weight: 600; cursor: pointer;
        }
        .btn-back {
            background: #e2e8f0; color: #334155; text-decoration: none;
            padding: 14px 24px; border-radius: 10px; font-size: 16px;
            font-weight: 600; margin-right: 10px;
        }
        .error { color: #ef4444; font-size: 13px; margin-top: 5px; }
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
                <h1>Tambah Buku</h1>
            </div>
            <div class="form-card">
                <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="field">
                        <label>Judul Buku</label>
                        <input type="text" name="title" value="{{ old('title') }}" required>
                        @error('title')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Penulis</label>
                        <input type="text" name="author" value="{{ old('author') }}" required>
                        @error('author')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Kategori</label>
                        <select name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0" required>
                        @error('stock')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Sinopsis</label>
                        <textarea name="description">{{ old('description') }}</textarea>
                        @error('description')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Gambar Buku</label>
                        <input type="file" name="image" accept="image/*">
                        @error('image')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div style="margin-top: 10px;">
                        <a href="{{ route('admin.books') }}" class="btn-back">Kembali</a>
                        <button type="submit" class="btn-submit">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

