<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login User - BUKA BUKU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            min-height: 100vh; font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 16px;
        }
        .login-shell {
            width: 100%; max-width: 1100px;
            display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 0;
            background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 25px 80px rgba(15, 23, 42, 0.12);
        }
        .login-sidebar {
            background: #0f172a; color: white; padding: 40px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .login-sidebar h2 { font-size: 26px; margin-bottom: 16px; }
        .login-sidebar p { color: #cbd5e1; line-height: 1.7; margin-bottom: 24px; font-size: 15px; }
        .sidebar-links { list-style: none; display: grid; gap: 12px; }
        .sidebar-links li { font-size: 14px; color: #cbd5e1; }
        .sidebar-links li::before { content: '•'; color: #f97316; margin-right: 10px; }
        .login-card { padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .login-card h1 { font-size: 28px; color: #0f172a; margin-bottom: 8px; }
        .login-card > p { color: #475569; margin-bottom: 28px; font-size: 14px; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px; }
        .field input {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 12px;
            padding: 14px 16px; font-size: 16px; color: #0f172a; outline: none;
            transition: border-color .2s; min-height: 48px;
        }
        .field input:focus { border-color: #f97316; }
        .btn-submit {
            width: 100%; border: none; border-radius: 12px; padding: 14px;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            color: white; font-size: 16px; font-weight: 700; cursor: pointer;
            transition: transform .2s, box-shadow .2s; min-height: 48px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(249, 115, 22, .3); }
        .btn-submit:active { transform: translateY(0); }
        .message { margin-top: 14px; font-size: 13px; color: #ef4444; }
        .hint { margin-top: 12px; font-size: 13px; color: #64748b; text-align: center; }
        .admin-link { margin-top: 18px; text-align: center; }
        .admin-link a {
            color: #f97316; text-decoration: none; font-weight: 600; font-size: 14px;
        }
        .admin-link a:hover { text-decoration: underline; }

        @media (max-width: 960px) {
            .login-shell { grid-template-columns: 1fr; max-width: 480px; }
            .login-sidebar { padding: 32px 28px; }
            .login-sidebar h2 { font-size: 22px; }
            .login-card { padding: 32px 28px; }
            .login-card h1 { font-size: 24px; }
        }
        @media (max-width: 480px) {
            body { padding: 12px; }
            .login-shell { border-radius: 16px; }
            .login-sidebar { padding: 24px 20px; }
            .login-sidebar h2 { font-size: 20px; }
            .login-sidebar p { font-size: 14px; }
            .login-card { padding: 24px 20px; }
            .login-card h1 { font-size: 22px; }
            .field input { padding: 12px 14px; font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-sidebar">
            <div>
                <h2>BUKA BUKU APPS</h2>
                <p>Masuk dengan NPM dan password kamu untuk mulai memilih buku dan mengajukan peminjaman.</p>
            </div>
            <!-- <ul class="sidebar-links">
                <li>Rekomendasi buku populer</li>
                <li>Proses peminjaman cepat</li>
                <li>Ambil buku di kasir</li>
                <li>Konfirmasi tanpa ribet</li>
            </ul> -->
        </div>

        <div class="login-card">
            <h1>Login User</h1>
            <p>Gunakan NPM dan password kamu untuk masuk.</p>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="field">
                    <label for="npm">NPM</label>
                    <input id="npm" type="text" name="npm" value="{{ old('npm') }}" placeholder="Contoh: 109230640050" required>
                    @error('npm')
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

            <p class="hint">Hubungi admin jika belum memiliki akun.</p>

            <div class="admin-link">
                <a href="{{ route('admin.login') }}">Masuk sebagai Admin →</a>
            </div>
        </div>
    </div>
</body>
</html>

