@extends('layouts.admin')

@section('title', 'Management Membership - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Management Membership</h1>
</div>

@if(session('success'))
    <div class="success-msg">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM/NPM</th>
                    <th>Status</th>
                    <th>Paket Premium</th>
                    <th>Expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $isPremiumActive = $user->membership_type === 'premium'
                            && $user->premium_expired_date
                            && \Carbon\Carbon::parse($user->premium_expired_date)->greaterThanOrEqualTo(now());

                        $statusBadgeClass = $isPremiumActive ? 'badge-premium-active' : ($user->membership_type === 'premium' ? 'badge-premium-expired' : 'badge-standar');
                        $statusLabel = $isPremiumActive ? 'Premium Active' : ($user->membership_type === 'premium' ? 'Premium Expired' : 'Standar');
                    @endphp

                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nim ?? $user->npm ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            {{ $user->premium_package ? ($user->premium_package . ' bulan') : '-' }}
                        </td>
                        <td>
                            {{ $user->premium_expired_date ? \Carbon\Carbon::parse($user->premium_expired_date)->format('d M Y') : '-' }}
                        </td>
                        <td>
                            @if($user->membership_type !== 'premium' || !$isPremiumActive)
                                <form method="POST" action="{{ route('admin.memberships.set-premium', $user->id) }}" style="display:flex;gap:8px;flex-wrap:wrap;">
                                    @csrf
                                    <select name="premium_package" style="padding:8px 10px;border:1px solid #ddd;border-radius:8px;font-size:13px;">
                                        <option value="3">3 bulan - Rp15.000</option>
                                        <option value="6">6 bulan - Rp25.000</option>
                                        <option value="12">12 bulan - Rp50.000</option>
                                    </select>
                                    <button type="submit" class="btn btn-edit" style="padding:8px 12px;font-size:13px;">Jadikan Premium</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.memberships.set-standar', $user->id) }}" style="margin-top:8px;">
                                @csrf
                                <button type="submit" class="btn btn-delete" style="padding:8px 12px;font-size:13px;background:#64748b; border:none;">
                                    Kembalikan Standar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#64748b;">Belum ada mahasiswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .badge { display:inline-flex; align-items:center; padding:6px 10px; border-radius:999px; font-weight:900; font-size:12px; }
    .badge-standar { background:#fff7ed; color:#9a3412; border:1px solid #fed7aa; }
    .badge-premium-active { background:#ecfdf5; color:#166534; border:1px solid #bbf7d0; }
    .badge-premium-expired { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }

    .table-wrap table { width:100%; border-collapse:collapse; }
    .table-wrap th { text-align:left; padding:12px 10px; background:#f8fafc; font-size:13px; color:#334155; }
    .table-wrap td { padding:12px 10px; border-top:1px solid #eef2f7; font-size:13px; vertical-align:top; }

    .btn { border:none; border-radius:10px; cursor:pointer; }
    .btn-edit { background:#3b82f6; color:white; }
    .btn-delete { background:#ef4444; color:white; }
    .success-msg { background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-weight:800; font-size:13px; text-align:center; }
</style>
@endsection

