# TODO - Make Website Responsive

## Plan Progress
- [x] User approved the plan
- [x] Create admin layout master
- [x] Create user layout master
- [x] Refactor admin/dashboard.blade.php
- [x] Refactor admin/books/index.blade.php
- [x] Refactor admin/books/create.blade.php
- [x] Refactor admin/books/edit.blade.php
- [x] Refactor admin/transactions/index.blade.php
- [x] Refactor admin/users/create.blade.php
- [x] Refactor books/index.blade.php
- [x] Fix auth/login.blade.php responsive
- [x] Fix auth/admin_login.blade.php responsive
- [x] Fix borrow/duration.blade.php responsive
- [x] Final check & test

## Summary of Changes
1. **Created `layouts/admin.blade.php`** — Responsive admin layout with collapsible sidebar, hamburger menu, overlay, scrollable tables, and mobile-optimized stats grid.
2. **Created `layouts/user.blade.php`** — Responsive user layout with collapsible sidebar, mobile topbar, responsive book grid (2 cols on tablet, 1 col on small phone), and borrow history cards.
3. **Refactored all admin views** to use `@extends('layouts.admin')`:
   - Dashboard
   - Books index, create, edit
   - Transactions index
   - Users create
4. **Refactored `books/index.blade.php`** to use `@extends('layouts.user')`.
5. **Fixed login pages** (`auth/login.blade.php` & `auth/admin_login.blade.php`):
   - Stack to single column on tablet/mobile
   - Reduced padding & font sizes on small screens
   - Larger touch targets (min 48px)
6. **Fixed `borrow/duration.blade.php`**:
   - Book preview wraps vertically on mobile
   - Buttons stack vertically on small screens
   - Modal stays centered and proportional
   - Reduced padding on mobile

## Responsive Breakpoints Used
- `max-width: 768px` — Tablet & mobile (sidebar hidden, hamburger shown, single column layouts)
- `max-width: 480px` — Small phones (further reduced padding, full-width grids)
- `max-width: 360px` — Very small phones (single column book grid)
- `max-width: 960px` — Login pages switch to single column

