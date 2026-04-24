@extends('layouts.admin')

@section('title', 'Kelola Buku - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Kelola Buku</h1>
    <a href="{{ route('admin.books.create') }}" class="btn btn-add">+ Tambah Buku</a>
</div>

@if(session('success'))
    <div class="success-msg">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-wrap">
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
                            <div class="book-img-placeholder">No Image</div>
                        @endif
                    </td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->category->name ?? '-' }}</td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-edit" style="padding:6px 12px;font-size:13px;">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" style="padding:6px 12px;font-size:13px;">Hapus</button>
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
@endsection

