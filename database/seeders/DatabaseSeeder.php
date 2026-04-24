<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin if not exists
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create sample categories if not exists
        $categories = [
            ['name' => 'Fiksi'],
            ['name' => 'Non-Fiksi'],
            ['name' => 'Teknologi'],
            ['name' => 'Sejarah'],
            ['name' => 'Sains'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(['name' => $category['name']]);
        }

        // Create sample books if not exists
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'category_id' => 1,
                'stock' => 5,
                'description' => 'Kisah inspiratif tentang perjuangan anak-anak di Belitung untuk mendapatkan pendidikan.',
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'category_id' => 3,
                'stock' => 3,
                'description' => 'Panduan praktis untuk menulis kode yang bersih dan mudah dipelihara.',
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'category_id' => 4,
                'stock' => 4,
                'description' => 'Sejarah singkat umat manusia dari zaman batu hingga era modern.',
            ],
        ];

        foreach ($books as $book) {
            \App\Models\Book::firstOrCreate(
                ['title' => $book['title']],
                $book
            );
        }
    }
}

