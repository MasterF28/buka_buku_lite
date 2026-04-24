<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Buku - BUKA BUKU</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #fff;
            border-right: 1px solid #eee;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 18px;
        }

        .sidebar-logo img {
            width: 30px;
            height: 30px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #666;
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f0f0f0;
            color: #e63946;
            font-weight: 600;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e63946;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .logout-btn {
            background: #e63946;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #d62828;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-section label {
            font-weight: 600;
            color: #333;
        }

        .filter-section select,
        .filter-section input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .filter-section button {
            background: #e63946;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .filter-section button:hover {
            background: #d62828;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            cursor: pointer;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .book-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }

        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-info {
            padding: 15px;
        }

        .book-category {
            display: inline-block;
            background: #e63946;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .book-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .book-author {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .book-stock {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .select-btn {
            width: 100%;
            background: #e63946;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .select-btn:hover {
            background: #d62828;
        }

        .select-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .no-books {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                margin-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">
                📚 BUKA BUKU
            </div>
            <ul class="sidebar-menu">
                <li><a href="/books" class="active">🏠 Beranda</a></li>
                <li><a href="#riwayat">📋 Riwayat Peminjaman</a></li>
                <li><a href="#favorit">❤️ Buku Favorit</a></li>
                <li><a href="#notifikasi">🔔 Notifikasi</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Rekomendasi Buku</h1>
                <div class="user-info">
                    <div class="user-avatar">{{ substr(session('nim'), 0, 1) }}</div>
                    <div>
                        <p style="font-size: 14px; color: #666;">{{ session('nim') }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="filter-section">
                <label>Filter Kategori:</label>
                <select id="categoryFilter" onchange="filterByCategory()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="searchInput" placeholder="Cari judul atau penulis..." onkeyup="searchBooks()" style="flex: 1; max-width: 300px;">
            </div>

            <div class="books-grid" id="booksGrid">
                @if($books->count() > 0)
                    @foreach($books as $book)
                        <div class="book-card" data-category="{{ $book->category_id }}" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}">
                            <div class="book-image">
                                @if($book->image_url)
                                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                                @else
                                    {{ $book->title }}
                                @endif
                            </div>
                            <div class="book-info">
                                <div class="book-category">{{ $book->category->name ?? 'N/A' }}</div>
                                <div class="book-title" title="{{ $book->title }}">{{ $book->title }}</div>
                                <div class="book-author" title="{{ $book->author }}">{{ $book->author }}</div>
                                <div class="book-stock">
                                    Stock: <strong>{{ $book->stock }}</strong>
                                </div>
                                <form action="{{ route('books.select', $book->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="select-btn" {{ $book->stock == 0 ? 'disabled' : '' }}>
                                        {{ $book->stock == 0 ? 'Habis' : 'Pilih Buku' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-books" style="grid-column: 1 / -1;">
                        <p>Tidak ada buku tersedia</p>
                    </div>
                @endif
            </div>

            <div id="riwayat" class="borrow-history" style="margin-top: 40px;">
                <div class="header" style="justify-content: flex-start;">
                    <h2>📋 Riwayat Peminjaman Aktif</h2>
                </div>

                @if(isset($borrowedTransactions) && $borrowedTransactions->count())
                    <div class="history-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        @foreach($borrowedTransactions as $transaction)
                            <div class="history-card" style="background: white; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; gap: 16px; align-items: flex-start; transition: transform 0.2s;">
                                <div style="width: 70px; height: 100px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #e2e8f0;">
                                    @if($transaction->book->image_url)
                                        <img src="{{ $transaction->book->image_url }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $transaction->book->title }}">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #64748b; text-align: center; padding: 4px;">{{ Str::limit($transaction->book->title, 15) }}</div>
                                    @endif
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="book-title" style="white-space: normal; font-size: 16px; margin-bottom: 4px;">{{ $transaction->book->title }}</div>
                                    <div class="book-author" style="color: #64748b; font-size: 13px; margin-bottom: 10px;">Oleh {{ $transaction->book->author }}</div>

                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px; font-size: 13px;">
                                        <span style="color: #64748b;">📅 Jatuh tempo:</span>
                                        <strong style="color: #0f172a;">{{ $transaction->due_date->format('d M Y') }}</strong>
                                    </div>

                                    @if($transaction->late_days > 0)
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 12px;">
                                            ⚠️ Terlambat {{ $transaction->late_days }} hari
                                        </div>
                                        <div style="font-size: 13px; color: #dc2626; font-weight: 700; margin-bottom: 12px;">
                                            Denda: Rp {{ number_format($transaction->fine_amount, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 12px;">
                                            ✅ Tersisa {{ round($transaction->due_date->diffInDays(now())) }} hari
                                        </div>
                                    @endif

                                    <form action="{{ route('borrow.return', $transaction->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="select-btn" style="width: 100%; background: #16a34a; border-radius: 10px; padding: 10px; font-size: 14px;">
                                            Kembalikan Buku
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 40px; background: #fff; border-radius: 14px; text-align: center; color: #64748b; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                        <p>Tidak ada peminjaman aktif saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function filterByCategory() {
            const categoryId = document.getElementById('categoryFilter').value;
            const cards = document.querySelectorAll('.book-card');

            cards.forEach(card => {
                if (!categoryId || card.dataset.category == categoryId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchBooks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.book-card');

            cards.forEach(card => {
                const title = card.dataset.title;
                const author = card.dataset.author;

                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Check if user is logged in
        if (!{{ session()->has('user_id') ? 'true' : 'false' }}) {
            window.location.href = '/login';
        }
    </script>
</body>
</html>
