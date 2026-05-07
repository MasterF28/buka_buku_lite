<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BookReadController extends Controller
{
    public function read(Request $request, $bookId)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $user = User::find(Session::get('user_id'));
        if (!$user) {
            return redirect('/login');
        }

        $book = Book::findOrFail($bookId);
        if (!$book->pdf_file) {
            return redirect('/books')->with('error', 'PDF belum tersedia');
        }

        // Backend enforced membership check + auto-expire
        $now = Carbon::now();

        $isPremiumActive = $user->membership_type === 'premium'
            && $user->premium_expired_date
            && Carbon::parse($user->premium_expired_date)->greaterThanOrEqualTo($now);

        if ($user->membership_type === 'premium' && $user->premium_expired_date) {
            if (Carbon::parse($user->premium_expired_date)->lessThan($now)) {
                $user->update([
                    'membership_type' => 'standar',
                    'premium_package' => null,
                    'premium_start_date' => null,
                    'premium_expired_date' => null,
                ]);

                $isPremiumActive = false;
            }
        }

        $maxPage = $isPremiumActive ? null : 5;

        // Pastikan URL menggunakan disk public (/storage prefix) agar bisa di-fetch tanpa 404
        $pdfUrl = asset('storage/' . ltrim($book->pdf_file, '/'));

        return view('books.read', [
            'user' => $user,
            'book' => $book,
            'pdfUrl' => $pdfUrl,
            'isPremiumActive' => $isPremiumActive,
            'maxPage' => $maxPage,
        ]);
    }
}

