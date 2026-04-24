@extends('layouts.admin')

@section('title', 'Peminjaman - BUKA BUKU')

@section('content')
<div class="page-header">
    <h1>Pemantauan Peminjaman</h1>
</div>

<h2 style="font-size:18px;color:#334155;margin:24px 0 12px;">Peminjaman Aktif</h2>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Keterlambatan</th>
                    <th>Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $transaction->book->title ?? '-' }}</td>
                    <td>{{ $transaction->borrow_date->format('d M Y') }}</td>
                    <td>{{ $transaction->due_date->format('d M Y') }}</td>
                    <td>
                        @if($transaction->late_days > 0)
                            <span class="fine-warning">{{ $transaction->late_days }} hari</span>
                        @else
                            <span class="fine-safe">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->fine_amount > 0)
                            <span class="fine-warning">Rp {{ number_format($transaction->fine_amount, 0, ',', '.') }}</span>
                        @else
                            <span class="fine-safe">Rp 0</span>
                        @endif
                    </td>
                    <td><span class="badge badge-borrowed">Dipinjam</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#64748b;">Tidak ada peminjaman aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<h2 style="font-size:18px;color:#334155;margin:24px 0 12px;">Riwayat Pengembalian</h2>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returnedTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $transaction->book->title ?? '-' }}</td>
                    <td>{{ $transaction->borrow_date->format('d M Y') }}</td>
                    <td>{{ $transaction->return_date ? $transaction->return_date->format('d M Y') : '-' }}</td>
                    <td>
                        @php
                            $fine = 0;
                            if ($transaction->return_date && $transaction->return_date->greaterThan($transaction->due_date)) {
                                $lateDays = $transaction->return_date->diffInDays($transaction->due_date);
                                $fine = $lateDays * 5000;
                            }
                        @endphp
                        @if($fine > 0)
                            <span class="fine-warning">Rp {{ number_format($fine, 0, ',', '.') }}</span>
                        @else
                            <span class="fine-safe">Rp 0</span>
                        @endif
                    </td>
                    <td><span class="badge badge-returned">Dikembalikan</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#64748b;">Belum ada riwayat pengembalian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

