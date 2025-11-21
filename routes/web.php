<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommunityController; // 👈 AJOUT

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/debug-routes', function () {
    return 'OK ROUTES';
})->name('debug.routes');


Route::resource('users', UserController::class);

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Posts
    Route::get('/posts', [PostController::class,'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class,'destroy'])->name('posts.destroy');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👇 TOUTES les routes communautés
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::delete('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');
});

require __DIR__.'/auth.php';
