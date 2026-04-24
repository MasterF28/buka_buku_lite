<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pilih Durasi Pinjam - BUKA BUKU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 16px;
        }
        .container { max-width: 600px; width: 100%; }
        .card {
            background: white; border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,.2); overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 28px 24px; text-align: center;
        }
        .header h1 { font-size: 24px; margin-bottom: 6px; }
        .header p { opacity: .9; font-size: 14px; }
        .content { padding: 24px; }

        .book-preview {
            background: #f5f6fa; padding: 16px; border-radius: 12px;
            margin-bottom: 24px; display: flex; gap: 14px; align-items: center;
        }
        .book-image {
            width: 72px; height: 100px; border-radius: 8px; overflow: hidden;
            flex-shrink: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 10px; text-align: center; font-weight: bold;
        }
        .book-image img { width: 100%; height: 100%; object-fit: cover; }
        .book-details { flex: 1; min-width: 0; }
        .book-title { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 4px; }
        .book-author { font-size: 13px; color: #999; margin-bottom: 8px; }
        .book-category {
            display: inline-block; background: #e63946; color: white;
            padding: 3px 10px; border-radius: 20px; font-size: 11px;
        }

        .section-title {
            font-size: 17px; font-weight: 600; color: #333;
            margin-bottom: 16px;
        }
        .duration-options { display: grid; gap: 10px; margin-bottom: 24px; }
        .duration-option {
            display: flex; align-items: center; gap: 12px;
            padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px;
            cursor: pointer; transition: .2s; background: #f9fafb;
        }
        .duration-option:hover { border-color: #667eea; background: #eef2ff; }
        .duration-option input[type="radio"] {
            width: 20px; height: 20px; cursor: pointer; accent-color: #667eea; flex-shrink: 0;
        }
        .duration-option .info { flex: 1; }
        .duration-option .days { font-weight: 600; color: #111827; font-size: 15px; }
        .duration-option .desc { font-size: 12px; color: #6b7280; margin-top: 2px; }

        .info-box {
            background: #ecfdf5; border-left: 4px solid #10b981;
            padding: 14px 16px; border-radius: 8px; margin-bottom: 24px;
            font-size: 13px; color: #065f46; line-height: 1.6;
        }
        .info-box strong { display: block; margin-bottom: 4px; }

        .button-group {
            display: flex; gap: 10px; margin-top: 24px;
        }
        .btn {
            flex: 1; padding: 14px; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 600; cursor: pointer; transition: .2s;
            text-align: center; text-decoration: none; min-height: 48px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,.35); }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }

        .modal {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); align-items: center; justify-content: center;
            z-index: 1000; padding: 16px;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: white; padding: 32px 24px; border-radius: 16px;
            text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,.2);
            max-width: 360px; width: 100%; animation: slideUp .3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .modal-icon { font-size: 52px; margin-bottom: 14px; }
        .modal-title { font-size: 20px; font-weight: 600; color: #111827; margin-bottom: 10px; }
        .modal-message { font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 24px; }
        .modal-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; padding: 12px 28px;
            border-radius: 10px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: .2s;
        }
        .modal-button:hover { transform: translateY(-2px); }

        .loading { display: none; text-align: center; padding: 20px; }
        .spinner {
            border: 4px solid #f0f0f0; border-top: 4px solid #667eea;
            border-radius: 50%; width: 32px; height: 32px;
            animation: spin 1s linear infinite; margin: 0 auto 12px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .header { padding: 22px 18px; }
            .header h1 { font-size: 20px; }
            .content { padding: 18px; }
            .book-preview { flex-direction: column; text-align: center; }
            .book-image { width: 80px; height: 110px; }
            .button-group { flex-direction: column; }
            .duration-option { padding: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>📅 Pilih Durasi Peminjaman</h1>
                <p>Tentukan berapa lama Anda ingin meminjam buku ini</p>
            </div>

            <div class="content">
                <div class="book-preview">
                    <div class="book-image">
                        @if($book->image_url)
                            <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                        @else
                            {{ Str::limit($book->title, 15) }}
                        @endif
                    </div>
                    <div class="book-details">
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-author">Oleh {{ $book->author }}</div>
                        <div class="book-category">{{ $book->category->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <form id="borrowForm">
                    <div class="section-title">Pilih Durasi Pinjam</div>
                    <div class="duration-options">
                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="3" required>
                            <div class="info">
                                <div class="days">3 Hari</div>
                                <div class="desc">Durasi pinjam ideal untuk buku ringan</div>
                            </div>
                        </label>
                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="7" required checked>
                            <div class="info">
                                <div class="days">7 Hari (Rekomendasi)</div>
                                <div class="desc">Durasi pinjam standar perpustakaan</div>
                            </div>
                        </label>
                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="14" required>
                            <div class="info">
                                <div class="days">14 Hari</div>
                                <div class="desc">Durasi pinjam untuk buku tebal</div>
                            </div>
                        </label>
                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="30" required>
                            <div class="info">
                                <div class="days">30 Hari</div>
                                <div class="desc">Durasi pinjam maksimal</div>
                            </div>
                        </label>
                    </div>

                    <div class="info-box">
                        <strong>⏰ Informasi Penting:</strong>
                        Buku harus dikembalikan tepat pada tanggal jatuh tempo. Keterlambatan akan dikenakan denda.
                    </div>

                    <div class="button-group">
                        <a href="/books" class="btn btn-secondary">Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="submitBorrow()">Konfirmasi</button>
                    </div>
                </form>

                <div class="loading" id="loadingState">
                    <div class="spinner"></div>
                    <p>Memproses peminjaman...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-icon">✅</div>
            <div class="modal-title">Selamat Membaca!</div>
            <div class="modal-message">
                Buku bisa di ambil di kasir<br>
                <strong style="color:#111827;display:block;margin-top:8px;">Jangan lupa jatuh tempo pengembalian!</strong>
            </div>
            <button class="modal-button" onclick="completeProcess()">Selesai</button>
        </div>
    </div>

    <script>
        function submitBorrow() {
            const durationDays = parseInt(document.querySelector('input[name="duration_days"]:checked').value, 10);
            document.getElementById('borrowForm').style.display = 'none';
            document.getElementById('loadingState').style.display = 'block';

            fetch('{{ route("borrow.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ duration_days: durationDays })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('confirmationModal').classList.add('show');
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat memproses peminjaman');
                location.reload();
            });
        }
        function completeProcess() {
            window.location.href = '/books';
        }
        if (!{{ session()->has('user_id') ? 'true' : 'false' }}) {
            window.location.href = '/login';
        }
        if (!{{ session()->has('selected_book_id') ? 'true' : 'false' }}) {
            window.location.href = '/books';
        }
    </script>
</body>
</html>

