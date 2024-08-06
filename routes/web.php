<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ProfileController;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $blogPost = BlogPost::latest()->first();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'blogPost' => $blogPost
    ]);
});

Route::get('/dashboard', function () {
    $blogPosts = BlogPost::with('user')->latest()->paginate(2);
    $user = Auth::user();
    $userPostCount = $user ? $user->blogPosts()->count() : 0;
    $totalUserCount = User::count();
    $totalBlogPostCount = BlogPost::count();
    return Inertia::render('Dashboard',
    [
        'blogPosts'=>$blogPosts,
        'userPostCount' => $userPostCount,
        'totalUserCount' => $totalUserCount,
        'totalBlogPostCount' => $totalBlogPostCount
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    //blog post routes

    Route::get('/blog/create', [BlogPostController::class, 'create'])->name('blog.create');
    Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
    Route::post('/blog', [BlogPostController::class, 'store'])->name('blog.store');
    Route::post('/blog/create', [BlogPostController::class, 'askai'])->name('blog.askai');
    Route::get('/blog/{blogPost}', [BlogPostController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/{blogPost}', [BlogPostController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blogPost}', [BlogPostController::class, 'destroy'])->name('blog.destroy');



    Route::post('/blog/{blogPost}/toggle-favorite', [BlogPostController::class, 'toggleFavorite'])->name('favorite.add');
    Route::get('/favorite/index', [BlogPostController::class, 'favindex'])->name('favorite.index');

});

require __DIR__.'/auth.php';
