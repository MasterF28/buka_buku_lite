@extends('layouts.user')

@section('title', 'Profil Saya - BUKA BUKU')

@push('styles')
<style>
    .profile-card {
        background: white; border-radius: 16px; padding: 32px;
        box-shadow: 0 4px 12px rgba(0,0,0,.08); max-width: 480px; margin: 0 auto;
        text-align: center;
    }
    .profile-avatar {
        width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px;
        background: #e63946; color: white; display: flex; align-items: center; justify-content: center;
        font-size: 48px; font-weight: 700; overflow: hidden; position: relative;
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .profile-name { font-size: 22px; font-weight: 700; color: #333; margin-bottom: 4px; }
    .profile-nim { font-size: 14px; color: #666; margin-bottom: 24px; }
    .profile-form { text-align: left; }
    .profile-form .field { margin-bottom: 16px; }
    .profile-form label { display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 6px; }
    .profile-form input[type="text"], .profile-form input[type="file"] {
        width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 10px;
        font-size: 14px; outline: none; font-family: inherit;
    }
    .profile-form input[type="text"]:focus { border-color: #e63946; }
    .btn-save {
        width: 100%; background: #e63946; color: white; border: none;
        padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600;
        cursor: pointer; transition: .2s;
    }
    .btn-save:hover { background: #d62828; }
    .btn-logout {
        width: 100%; background: transparent; color: #e63946; border: 2px solid #e63946;
        padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600;
        cursor: pointer; transition: .2s; margin-top: 12px;
    }
    .btn-logout:hover { background: #fef2f2; }
    .success-msg {
        background: #dcfce7; color: #166534; padding: 12px 16px;
        border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;
    }
    .error-msg {
        background: #fef2f2; color: #dc2626; padding: 12px 16px;
        border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;
    }
    .current-photo-label { font-size: 12px; color: #999; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>👤 Profil Saya</h1>
</div>

<div class="profile-card">
    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    <div class="profile-avatar">
        @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
            <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil">
        @else
            {{ strtoupper(substr($user->name, 0, 1)) }}
        @endif
    </div>

    <div class="profile-name">{{ $user->name }}</div>
    <div class="profile-nim">
        @if($user->nim)
            NIM: {{ $user->nim }}
        @elseif($user->npm)
            NPM: {{ $user->npm }}
        @endif
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf

        <div class="field">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="photo">Foto Profil</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            @error('photo')
                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
            @if($user->photo)
                <div class="current-photo-label">Kosongkan jika tidak ingin mengubah foto.</div>
            @endif
        </div>

        <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
    </form>

    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="btn-logout">🚪 Logout</button>
    </form>
</div>
@endsection

