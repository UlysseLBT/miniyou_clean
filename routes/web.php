<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommunityJoinRequestController;
use App\Http\Controllers\CommunityInvitationController;

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');



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
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👇 TOUTES les routes communautés
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::delete('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');
    Route::get('/communities/{community}/posts/create', [PostController::class, 'create'])->name('communities.posts.create');
    Route::delete('/communities/{community}', [CommunityController::class, 'destroy'])->name('communities.destroy');

     Route::post('/communities/{community}/join-requests', [CommunityJoinRequestController::class, 'store'])
        ->name('communities.joinRequests.store');
    Route::post('/communities/{community}/join-requests/cancel', [CommunityJoinRequestController::class, 'cancel'])
        ->name('communities.joinRequests.cancel');
    Route::post('/communities/{community}/join-requests/{joinRequest}/approve', [CommunityJoinRequestController::class, 'approve'])
        ->name('communities.joinRequests.approve');
    Route::post('/communities/{community}/join-requests/{joinRequest}/deny', [CommunityJoinRequestController::class, 'deny'])
        ->name('communities.joinRequests.deny');
    // invitations
    Route::post('/communities/{community}/invitations', [CommunityInvitationController::class, 'store'])
        ->name('communities.invitations.store');
    Route::post('/communities/{community}/invitations/{invitation}/revoke', [CommunityInvitationController::class, 'revoke'])
        ->name('communities.invitations.revoke');
    // accepter invitation via lien
    Route::get('/invitations/{token}/accept', [CommunityInvitationController::class, 'accept'])
        ->name('invitations.accept');
  

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');
    
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy');
    
    Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])
    ->name('posts.like');
});

require __DIR__.'/auth.php';
