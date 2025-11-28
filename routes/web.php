<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::get('/test/{post}', function (Post $post) {

    return Storage::download($post->image_path);
})->name('test');
// $path = 'articles/possimus-cumque-ea-sint-non_copy.png';
// $targetPath = 'posts/possimus-cumque-ea-sint-non_copy.png';

// Storage::move($path, $targetPath);

// return 'File copied successfully from ' . $path . ' to ' . $targetPath;


Route::get('/env-check', function () {
    dd(config('cloudinary'));
});


Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');