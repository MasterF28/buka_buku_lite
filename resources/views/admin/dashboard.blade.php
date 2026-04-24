@extends('layouts.admin')

@section('title', 'Dashboard Admin - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Dashboard Admin</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Buku</h3>
        <div class="number blue">{{ $totalBooks }}</div>
    </div>
    <div class="stat-card">
        <h3>Total User</h3>
        <div class="number green">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card">
        <h3>Peminjaman Aktif</h3>
        <div class="number amber">{{ $activeTransactions }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Denda (Aktif)</h3>
        <div class="number red">Rp {{ number_format($totalFines, 0, ',', '.') }}</div>
    </div>
</div>
@endsection

