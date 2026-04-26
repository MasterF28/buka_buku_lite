@extends('layouts.user')

@section('title', 'Pemilihan Buku - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Rekomendasi Buku</h1>
</div>

<div class="filter-section">
    <label>Filter Kategori:</label>
    <select id="categoryFilter" onchange="filterByCategory()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <input type="text" id="searchInput" placeholder="Cari judul atau penulis..." onkeyup="searchBooks()">
</div>

<div class="books-grid" id="booksGrid">
    @if($books->count() > 0)
        @foreach($books as $book)
            <div class="book-card" data-category="{{ $book->category_id }}" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}">
                <div class="book-image">
                    @if($book->image_url)
                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                    @else
                        {{ Str::limit($book->title, 20) }}
                    @endif
                </div>
                <div class="book-info">
                    <div class="book-category">{{ $book->category->name ?? 'N/A' }}</div>
                    <div class="book-title" title="{{ $book->title }}">{{ $book->title }}</div>
                    <div class="book-author" title="{{ $book->author }}">{{ $book->author }}</div>
                    <div class="book-stock">Stock: <strong>{{ $book->stock }}</strong></div>
                    <form action="{{ route('books.select', $book->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="select-btn" {{ $book->stock == 0 ? 'disabled' : '' }}>
                            {{ $book->stock == 0 ? 'Habis' : 'Pilih Buku' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <div class="no-books">
            <p>Tidak ada buku tersedia</p>
        </div>
    @endif
</div>

<div id="riwayat" style="margin-top:40px;">
    <div class="page-header" style="justify-content:flex-start;">
        <h2 style="font-size:20px;">📋 Riwayat Peminjaman Aktif</h2>
    </div>

    @if(isset($borrowedTransactions) && $borrowedTransactions->count())
        <div class="history-grid">
            @foreach($borrowedTransactions as $transaction)
                <div class="history-card">
                    <div class="history-thumb">
                        @if($transaction->book->image_url)
                            <img src="{{ $transaction->book->image_url }}" alt="{{ $transaction->book->title }}">
                        @else
                            <div class="history-thumb-placeholder">{{ Str::limit($transaction->book->title, 12) }}</div>
                        @endif
                    </div>
                    <div class="history-body">
                        <div class="title">{{ $transaction->book->title }}</div>
                        <div class="author">Oleh {{ $transaction->book->author }}</div>

                        <div class="history-meta">
                            <span class="label">📅 Jatuh tempo:</span>
                            <span class="value">{{ $transaction->due_date->format('d M Y') }}</span>
                        </div>

                        @if($transaction->late_days > 0)
                            <div class="badge-status late">⚠️ Terlambat {{ $transaction->late_days }} hari</div>
                            <div class="fine-text">Denda: Rp {{ number_format($transaction->fine_amount, 0, ',', '.') }}</div>
                        @else
                            <div class="badge-status safe">✅ Tersisa {{ round($transaction->due_date->diffInDays(now())) }} hari</div>
                        @endif

                        <form action="{{ route('borrow.return', $transaction->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="select-btn return" style="width:100%;">Kembalikan Buku</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>Tidak ada peminjaman aktif saat ini.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function filterByCategory() {
        const categoryId = document.getElementById('categoryFilter').value;
        document.querySelectorAll('.book-card').forEach(card => {
            card.style.display = (!categoryId || card.dataset.category == categoryId) ? 'flex' : 'none';
        });
    }
    function searchBooks() {
        const term = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.book-card').forEach(card => {
            card.style.display = (card.dataset.title.includes(term) || card.dataset.author.includes(term)) ? 'flex' : 'none';
        });
    }
    if (!{{ session()->has('user_id') ? 'true' : 'false' }}) {
        window.location.href = '/login';
    }
</script>
@endpush

