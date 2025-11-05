<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;

// --- Public
Route::get('/', fn () => view('welcome'))->name('home');

// Formulaire de contact PUBLIC
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// --- Protégé
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Posts
    Route::get('/posts', [PostController::class,'index'])->name('posts.index');
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class,'destroy'])->name('posts.destroy');

    // Media
    Route::get('/media', [MediaController::class,'index'])->name('media.index');
    Route::post('/media', [MediaController::class,'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class,'destroy'])->name('media.destroy');

    // Profil
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
});

// ⚠️ Laisser auth.php EN DEHORS de tout groupe 'auth'
require __DIR__.'/auth.php';
