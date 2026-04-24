@extends('layouts.admin')

@section('title', 'Tambah Buku - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Tambah Buku</h1>
</div>

<div class="card form-card">
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <label>Judul Buku</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Masukkan judul buku" required>
            @error('title')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Penulis</label>
            <input type="text" name="author" value="{{ old('author') }}" placeholder="Nama penulis" required>
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
            <textarea name="description" placeholder="Tulis sinopsis singkat...">{{ old('description') }}</textarea>
            @error('description')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label>Gambar Buku</label>
            <input type="file" name="image" accept="image/*" style="padding:8px 0;border:none;">
            @error('image')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
            <a href="{{ route('admin.books') }}" class="btn btn-back">Kembali</a>
            <button type="submit" class="btn btn-submit">Simpan Buku</button>
        </div>
    </form>
</div>
@endsection

