<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - BUKA BUKU</title>
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
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-width: 500px;
        }
        .field { margin-bottom: 20px; }
        .field label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; }
        .field input {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 10px;
            padding: 12px; font-size: 15px; outline: none;
        }
        .field input:focus { border-color: #f97316; }
        .btn-submit {
            background: #10b981; color: white; border: none;
            padding: 14px 24px; border-radius: 10px; font-size: 16px;
            font-weight: 600; cursor: pointer;
        }
        .error { color: #ef4444; font-size: 13px; margin-top: 5px; }
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
                <li><a href="{{ route('admin.books') }}">📚 Kelola Buku</a></li>
                <li><a href="{{ route('admin.transactions') }}">📋 Peminjaman</a></li>
                <li><a href="{{ route('admin.users.create') }}" class="active">👤 Daftar User</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Daftarkan User Baru</h1>
            </div>
            @if(session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif
            <div class="form-card">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label>NPM</label>
                        <input type="text" name="npm" value="{{ old('npm') }}" placeholder="Contoh: 109230640050" required>
                        @error('npm')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama mahasiswa" required>
                        @error('name')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn-submit">Daftarkan User</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

