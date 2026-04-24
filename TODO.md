# TODO - Implementasi Login Terpisah User (NPM+Password) & Admin (Username+Password)

## Database
- [x] Migration: Tambah kolom `username` ke tabel `users` untuk login admin

## Model
- [x] Update User Model: fillable, hidden, casts

## Middleware
- [x] Buat CheckRole middleware untuk membatasi akses admin/mahasiswa
- [x] Register middleware di bootstrap/app.php

## Controller
- [x] Refactor AuthController: pisahkan login user (NPM+password) dan admin (username+password)
- [x] Buat AdminController: dashboard, CRUD buku, monitoring transaksi, register user

## Routes
- [x] Update routes/web.php: group by role, admin routes

## Views - Auth
- [x] Update login.blade.php: form NPM + password (user)
- [x] Buat admin_login.blade.php: form username + password (admin)

## Views - Admin
- [x] Buat admin/dashboard.blade.php
- [x] Buat admin/books/index.blade.php
- [x] Buat admin/books/create.blade.php
- [x] Buat admin/books/edit.blade.php
- [x] Buat admin/transactions/index.blade.php
- [x] Buat admin/users/create.blade.php

## Seeder
- [x] Buat DatabaseSeeder dengan admin default

## Testing
- [x] Jalankan migration
- [x] Jalankan seeder
- [x] Test login user
- [x] Test login admin

