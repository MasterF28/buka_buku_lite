<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Durasi Pinjam - BUKA BUKU</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 30px;
        }

        .book-preview {
            background: #f5f6fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }

        .book-image {
            width: 80px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            text-align: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-details {
            flex: 1;
        }

        .book-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .book-author {
            font-size: 13px;
            color: #999;
            margin-bottom: 10px;
        }

        .book-category {
            display: inline-block;
            background: #e63946;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .duration-options {
            display: grid;
            gap: 12px;
            margin-bottom: 30px;
        }

        .duration-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            background: #f9f9f9;
        }

        .duration-option:hover {
            border-color: #e63946;
            background: #fff5f5;
        }

        .duration-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #e63946;
        }

        .duration-option label {
            flex: 1;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .duration-option label .duration-days {
            font-weight: 600;
            color: #333;
        }

        .duration-option label .duration-period {
            font-size: 12px;
            color: #999;
        }

        .info-box {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #2e7d32;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .modal-message {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .modal-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .modal-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .loading {
            display: none;
            text-align: center;
        }

        .spinner {
            border: 4px solid #f0f0f0;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                            {{ $book->title }}
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
                            <div style="flex: 1;">
                                <div class="duration-days">3 Hari</div>
                                <div class="duration-period">Durasi pinjam ideal untuk buku ringan</div>
                            </div>
                        </label>

                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="7" required checked>
                            <div style="flex: 1;">
                                <div class="duration-days">7 Hari (Rekomendasi)</div>
                                <div class="duration-period">Durasi pinjam standar perpustakaan</div>
                            </div>
                        </label>

                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="14" required>
                            <div style="flex: 1;">
                                <div class="duration-days">14 Hari</div>
                                <div class="duration-period">Durasi pinjam untuk buku tebal</div>
                            </div>
                        </label>

                        <label class="duration-option">
                            <input type="radio" name="duration_days" value="30" required>
                            <div style="flex: 1;">
                                <div class="duration-days">30 Hari</div>
                                <div class="duration-period">Durasi pinjam maksimal</div>
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

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-icon">✅</div>
            <div class="modal-title">Selamat Membaca!</div>
            <div class="modal-message">
                Buku bisa di ambil di kasir<br>
                <strong style="color: #333; display: block; margin-top: 10px;">Jangan lupa jatuh tempo pengembalian!</strong>
            </div>
            <button class="modal-button" onclick="completeProcess()">Selesai</button>
        </div>
    </div>

    <script>
        function submitBorrow() {
            const durationDays = parseInt(document.querySelector('input[name="duration_days"]:checked').value, 10);

            // Show loading state
            document.getElementById('borrowForm').style.display = 'none';
            document.getElementById('loadingState').style.display = 'block';

            // Send AJAX request
            fetch('{{ route("borrow.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    duration_days: durationDays
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide loading and show modal
                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('confirmationModal').classList.add('show');
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses peminjaman');
                location.reload();
            });
        }

        function completeProcess() {
            // Redirect to books page
            window.location.href = '/books';
        }

        // Check if user is logged in
        if (!{{ session()->has('user_id') ? 'true' : 'false' }}) {
            window.location.href = '/login';
        }

        // Check if book is selected
        if (!{{ session()->has('selected_book_id') ? 'true' : 'false' }}) {
            window.location.href = '/books';
        }
    </script>
</body>
</html>
