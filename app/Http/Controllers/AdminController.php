<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ===================== DASHBOARD =====================
    public function dashboard()
    {
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'mahasiswa')->count();
        $activeTransactions = Transaction::where('status', 'borrowed')->count();
        $totalFines = Transaction::where('status', 'borrowed')
            ->get()
            ->sum('fine_amount');

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'activeTransactions', 'totalFines'));
    }

    // ===================== BOOKS CRUD =====================
    public function books()
    {
        $books = Book::with('category')->get();
        return view('admin.books.index', compact('books'));
    }

    public function createBook()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['title', 'author', 'category_id', 'stock', 'description']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        Book::create($data);

        return redirect('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function editBook($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $book = Book::findOrFail($id);
        $data = $request->only(['title', 'author', 'category_id', 'stock', 'description']);

        if ($request->hasFile('image')) {
            if ($book->image_url) {
                $oldPath = str_replace('/storage', 'public', $book->image_url);
                Storage::delete($oldPath);
            }
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        $book->update($data);

        return redirect('/admin/books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroyBook($id)
    {
        $book = Book::findOrFail($id);
        if ($book->image_url) {
            $oldPath = str_replace('/storage', 'public', $book->image_url);
            Storage::delete($oldPath);
        }
        $book->delete();

        return redirect('/admin/books')->with('success', 'Buku berhasil dihapus.');
    }

    // ===================== TRANSACTIONS MONITORING =====================
    public function transactions()
    {
        $transactions = Transaction::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->orderBy('due_date')
            ->get();

        $returnedTransactions = Transaction::with(['user', 'book'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc')
            ->get();

        return view('admin.transactions.index', compact('transactions', 'returnedTransactions'));
    }

    // ===================== REGISTER USER =====================
    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|unique:users,npm|unique:users,nim',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'npm' => $request->npm,
            'nim' => $request->npm,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return redirect('/admin/users/create')->with('success', 'User berhasil didaftarkan.');
    }
}

