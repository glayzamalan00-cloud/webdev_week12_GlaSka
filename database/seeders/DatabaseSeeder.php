<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Glayza Malan',
            'email' => 'glayza@gmail.com',
            'password' => 'cutiee',
        ]);

        $user2 = User::create([
            'name' => 'Grezelle Malan',
            'email' => 'grezelle@gmail.com',
            'password' => 'cutiee',
        ]);

        $user1->books()->createMany([
            ['title' => 'I Love You Since 1892',  'author' => 'Binibining Mia',      'status' => 'finished', 'rating' => 5],
            ['title' => 'The Rain in España',       'author' => 'Gwy Saludes', 'status' => 'reading',  'rating' => null],
            ['title' => 'After the Chains',    'author' => 'Jonaxx',      'status' => 'to-read',  'rating' => null],
        ]);

        $user2->books()->createMany([
            ['title' => 'Chasing in the Wild', 'author' => 'Gwy Saludes',    'status' => 'to-read',  'rating' => null],
            ['title' => 'Our Yesterday\'s Escape', 'author' => 'Gwy Saludes', 'status' => 'reading', 'rating' => null],
            ['title' => 'Avenues of the Diamond', 'author' => 'Gwy Saludes',    'status' => 'finished', 'rating' => 4],
        ]);
    }
}