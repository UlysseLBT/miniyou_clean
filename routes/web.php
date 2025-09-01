<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/posts', [PostController::class,'index'])->name('posts.index');
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class,'destroy'])->name('posts.destroy');

    Route::get('/media', [MediaController::class,'index'])->name('media.index');
    Route::post('/media', [MediaController::class,'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class,'destroy'])->name('media.destroy');

    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
