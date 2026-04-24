<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $categories = Category::with('books')->get();
        $books = Book::all();

        $borrowedTransactions = Transaction::with('book')
            ->where('user_id', session('user_id'))
            ->where('status', 'borrowed')
            ->orderBy('due_date')
            ->get();

        return view('books.index', compact('books', 'categories', 'borrowedTransactions'));
    }

    public function selectBook(Request $request, $bookId)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $book = Book::findOrFail($bookId);

        session()->put('selected_book_id', $book->id);

        return redirect('/borrow/duration');
    }
}
