# TODO Membership Digital Library (buka_buku_lite)

## Database
- [ ] Tambahkan migration: add_membership_fields_to_users
- [ ] Tambahkan migration: add_pdf_file_to_books

## Backend Logic
- [ ] Update model User & Book (fillable + helper method membership status)
- [ ] Tambahkan controller untuk PDF reader: akses kontrol halaman (standar max 5)
- [ ] Implement auto-expired saat akses reader (dan/atau saat login)

## Routes
- [ ] Tambahkan route user: GET /books/{book}/read (PDF reader)
- [ ] Tambahkan route admin: GET /admin/memberships
- [ ] Tambahkan route admin: POST /admin/memberships/{user}/set-premium
- [ ] Tambahkan route admin: POST /admin/memberships/{user}/set-standar

## Admin Upload PDF
- [ ] Update AdminController: storeBook + updateBook untuk upload pdf (mimes:pdf, max ~20MB)
- [ ] Update views admin/books/create.blade.php & edit.blade.php untuk field upload pdf

## Admin Membership Management
- [ ] Buat view admin/memberships/index.blade.php
- [ ] Buat UI tombol set premium (3/6/12 bulan) & set standar

## PDF Reader (PDF.js)
- [ ] Buat view books/read.blade.php memakai PDF.js render per-page
- [ ] Untuk standar: izinkan render halaman 1–5, sisanya blur/termasuk placeholder + tombol Upgrade
- [ ] Untuk premium: render semua halaman

## User UI (Optional)
- [ ] Update profile/show.blade.php menampilkan badge membership + paket + expired date + tombol hubungi admin

## Testing
- [ ] Migrate database
- [ ] Test admin upload PDF
- [ ] Test membership standar/premium di reader
- [ ] Test auto-expired dengan set tanggal lampau

