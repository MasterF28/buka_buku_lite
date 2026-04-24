<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function showDuration()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        if (!session()->has('selected_book_id')) {
            return redirect('/books');
        }

        $book = Book::findOrFail(session('selected_book_id'));

        return view('borrow.duration', compact('book'));
    }

    public function confirmBorrow(Request $request)
    {
        $request->validate([
            'duration_days' => 'required|integer|min:1|max:30'
        ]);

        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $userId = session('user_id');
        $bookId = session('selected_book_id');
        $durationDays = (int) $request->duration_days;

        $book = Book::findOrFail($bookId);

        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok buku sudah habis.'
            ], 422);
        }

        $borrowDate = Carbon::now();
        $dueDate = $borrowDate->copy()->addDays($durationDays);

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'status' => 'borrowed',
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'duration_days' => $durationDays
        ]);

        $book->decrement('stock');

        // Clear session data
        session()->forget(['selected_book_id', 'selected_book']);

        return response()->json([
            'success' => true,
            'message' => 'Selamat Membaca, Buku bisa di ambil di kasir'
        ]);
    }

    public function returnBook($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->user_id !== session('user_id')) {
            return redirect('/login');
        }

        if ($transaction->status !== 'borrowed') {
            return redirect('/books')->with('success', 'Transaksi ini sudah dikembalikan sebelumnya.');
        }

        $returnDate = Carbon::now();
        $lateDays = 0;
        $fineAmount = 0;

        if ($returnDate->greaterThan($transaction->due_date)) {
            $lateDays = $returnDate->diffInDays($transaction->due_date);
            $fineAmount = $lateDays * 5000;
        }

        $transaction->update([
            'status' => 'returned',
            'return_date' => $returnDate
        ]);

        $transaction->book->increment('stock');

        $message = 'Buku berhasil dikembalikan.';
        if ($fineAmount > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($fineAmount, 0, ',', '.');
        }

        return redirect('/books')->with('success', $message);
    }
}
