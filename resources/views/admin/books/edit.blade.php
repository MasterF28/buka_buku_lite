@extends('layouts.admin')

@section('title', 'Edit Buku - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Edit Buku</h1>
</div>

<div class="card form-card">
    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="field">
            <label>Judul Buku</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" required>
            @error('title')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Penulis</label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}" required>
            @error('author')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Kategori</label>
            <select name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required>
            @error('stock')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Sinopsis</label>
            <textarea name="description">{{ old('description', $book->description) }}</textarea>
            @error('description')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Gambar Buku</label>
            @if($book->image_url)
                <img src="{{ $book->image_url }}" class="current-img" alt="Current Image">
            @endif
            <input type="file" name="image" accept="image/*" style="margin-top:10px;padding:8px 0;border:none;">
            <small style="color:#64748b;font-size:13px;">Kosongkan jika tidak ingin mengubah gambar.</small>
            @error('image')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>PDF Buku</label>
            @if($book->pdf_file)
                <div style="font-size:13px;color:#16a34a;font-weight:800;margin-bottom:8px;">PDF tersedia ✅</div>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:-4px;">File saat ini: {{ basename($book->pdf_file) }}</small>
            @else
                <div style="font-size:13px;color:#dc2626;font-weight:800;margin-bottom:8px;">PDF belum tersedia</div>
            @endif

            <input type="file" name="pdf" accept="application/pdf" style="margin-top:10px;padding:8px 0;border:none;">
            <small style="color:#64748b;font-size:13px;">Kosongkan jika tidak ingin mengubah PDF.</small>
            @error('pdf')<div class="error">{{ $message }}</div>@enderror
        </div>


        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
            <a href="{{ route('admin.books') }}" class="btn btn-back">Kembali</a>
            <button type="submit" class="btn btn-submit" style="background:#3b82f6;">Update Buku</button>
        </div>
    </form>
</div>
@endsection

