<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BUKA BUKU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-shell {
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        }
        .login-sidebar {
            background: #0f172a;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .login-sidebar h2 { font-size: 28px; margin-bottom: 24px; }
        .login-sidebar p { color: #cbd5e1; line-height: 1.8; margin-bottom: 30px; }
        .sidebar-links { list-style: none; display: grid; gap: 14px; }
        .sidebar-links li { font-size: 15px; color: #cbd5e1; }
        .sidebar-links li::before { content: '•'; color: #f97316; margin-right: 10px; }
        .login-card { padding: 40px; }
        .login-card h1 { font-size: 32px; color: #0f172a; margin-bottom: 10px; }
        .login-card p { color: #475569; margin-bottom: 30px; }
        .field { margin-bottom: 20px; }
        .field label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; }
        .field input {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 14px;
            padding: 16px; font-size: 16px; color: #0f172a; outline: none;
            transition: border-color 0.2s ease;
        }
        .field input:focus { border-color: #f97316; }
        .btn-submit {
            width: 100%; border: none; border-radius: 14px; padding: 16px;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            color: white; font-size: 16px; font-weight: 700; cursor: pointer;
            transition: transform 0.2s ease;
        }
        .btn-submit:hover { transform: translateY(-2px); }
        .message { margin-top: 18px; font-size: 14px; color: #ef4444; }
        .hint { margin-top: 12px; font-size: 13px; color: #64748b; }
        .user-link {
            margin-top: 20px; text-align: center;
        }
        .user-link a {
            color: #f97316; text-decoration: none; font-weight: 600; font-size: 14px;
        }
        .user-link a:hover { text-decoration: underline; }
        @media (max-width: 960px) {
            .login-shell { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-sidebar">
            <div>
                <h2>ADMIN PANEL</h2>
                <p>Login sebagai admin untuk mengelola buku, memantau peminjaman, dan mendaftarkan user baru.</p>
            </div>
            <ul class="sidebar-links">
                <li>Kelola data buku</li>
                <li>Pantau peminjaman & denda</li>
                <li>Daftarkan user baru</li>
                <li>Lihat statistik perpustakaan</li>
            </ul>
        </div>

        <div class="login-card">
            <h1>Login Admin</h1>
            <p>Gunakan username dan password admin untuk masuk.</p>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required>
                    @error('username')
                        <div class="message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan password" required>
                    @error('password')
                        <div class="message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            @if(session('error'))
                <div class="message">{{ session('error') }}</div>
            @endif

            <div class="user-link">
                <a href="{{ route('login') }}">← Masuk sebagai User</a>
            </div>
        </div>
    </div>
</body>
</html>

