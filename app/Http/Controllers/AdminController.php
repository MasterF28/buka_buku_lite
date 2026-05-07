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
            'pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $data = $request->only(['title', 'author', 'category_id', 'stock', 'description']);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('books_pdfs', 'public');
            $data['pdf_file'] = $pdfPath;
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
            'pdf' => 'nullable|file|mimes:pdf|max:20480',
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

        if ($request->hasFile('pdf')) {
            if ($book->pdf_file) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $pdfPath = $request->file('pdf')->store('books_pdfs', 'public');
            $data['pdf_file'] = $pdfPath;
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

    // ===================== MEMBERSHIP MANAGEMENT =====================
    public function memberships()
    {
        $users = User::where('role', 'mahasiswa')->orderBy('name')->get();
        $premiumLabel = [
            3 => '3 bulan - Rp15.000',
            6 => '6 bulan - Rp25.000',
            12 => '12 bulan - Rp50.000',
        ];

        return view('admin.memberships.index', compact('users', 'premiumLabel'));
    }

    public function setPremium(Request $request, $userId)
    {
        $request->validate([
            'premium_package' => 'required|in:3,6,12',
        ]);

        $months = (int) $request->premium_package;
        $start = Carbon::now();
        $expired = $start->copy()->addMonths($months);

        $user = User::findOrFail($userId);
        $user->update([
            'membership_type' => 'premium',
            'premium_package' => $months,
            'premium_start_date' => $start,
            'premium_expired_date' => $expired,
        ]);

        return redirect('/admin/memberships')->with('success', 'Membership berhasil diubah menjadi Premium.');
    }

    public function setStandar($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'membership_type' => 'standar',
            'premium_package' => null,
            'premium_start_date' => null,
            'premium_expired_date' => null,
        ]);

        return redirect('/admin/memberships')->with('success', 'Membership berhasil dikembalikan menjadi Standar.');
    }
}


