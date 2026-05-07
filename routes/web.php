<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect('/login');
});

// ===================== USER AUTH (NPM + Password) =====================
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('login.submit');

// ===================== ADMIN AUTH (Username + Password) =====================
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================== USER ROUTES (Mahasiswa Only) =====================
Route::middleware(['auth.session', 'check.role:mahasiswa'])->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::post('/books/{book}/select', [BookController::class, 'selectBook'])->name('books.select');
    Route::get('/books/{book}/read', [\App\Http\Controllers\BookReadController::class, 'read'])->name('books.read');


    Route::get('/borrow/duration', [TransactionController::class, 'showDuration'])->name('borrow.duration');
    Route::post('/borrow/confirm', [TransactionController::class, 'confirmBorrow'])->name('borrow.confirm');
    Route::post('/borrow/{transaction}/return', [TransactionController::class, 'returnBook'])->name('borrow.return');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// ===================== ADMIN ROUTES (Admin Only) =====================
Route::middleware(['auth.session', 'check.role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Membership
    Route::get('/memberships', [AdminController::class, 'memberships'])->name('admin.memberships');
    Route::post('/memberships/{user}/set-premium', [AdminController::class, 'setPremium'])->name('admin.memberships.set-premium');
    Route::post('/memberships/{user}/set-standar', [AdminController::class, 'setStandar'])->name('admin.memberships.set-standar');


    // Books CRUD
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books');
    Route::get('/books/create', [AdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/books/{id}/edit', [AdminController::class, 'editBook'])->name('admin.books.edit');
    Route::put('/books/{id}', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/books/{id}', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');

    // Transactions Monitoring
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');

    // Register User
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
});

