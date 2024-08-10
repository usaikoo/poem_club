<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=> Hash::make('password')
        ]);

        BlogPost::create([
            'user_id'=>1,

            'title' => 'Eiusmod enim Lorem elit nisi velit anim. Aliqua officia quis aliquip ullamco elit exercitation ad ad aute labore. Dolor esse magna exercitation elit ut excepteur. Minim aliqua exercitation cillum quis commodo ut velit minim irure. Ipsum nostrud sit voluptate pariatur aliquip nostrud. Ipsum laboris laborum commodo ea occaecat esse incididunt.',
            'content' => 'Eiusmod deserunt sint tempor exercitation et anim tempor sunt. Reprehenderit sunt aute nulla deserunt consectetur proident cillum dolor ut. Do esse Lorem ullamco id veniam nulla veniam anim officia id culpa eiusmod et. Velit esse labore quis commodo non elit minim incididunt tempor fugiat excepteur. Proident voluptate consequat reprehenderit tempor proident deserunt culpa.',
        ]);
    }
}
