@extends('layouts.admin')

@section('title', 'Daftar User - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Daftarkan User Baru</h1>
</div>

@if(session('success'))
    <div class="success-msg">{{ session('success') }}</div>
@endif

<div class="card form-card">
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
        <button type="submit" class="btn btn-submit">Daftarkan User</button>
    </form>
</div>
@endsection

