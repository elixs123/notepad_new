<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{note_url}', [HomeController::class, 'show'])->name('notes.show');
Route::get('/share/{note_url}', [HomeController::class, 'shareUrl'])->name('notes.share');
Route::post('/update/{note_url}', [HomeController::class, 'update'])->name('notes.update');
Route::post('/unlock/{note_url}', [HomeController::class, 'unlock'])->name('notes.unlock');

Route::post('/upload', [HomeController::class, 'uploadFile'])->name('upload.file');


Route::get('search', [HomeController::class, 'search'])->name('search');

Route::get('/favorites', [HomeController::class, 'favorites'])->name('favorites');
Route::get('/changelog', function(){
    return view('changelog');
})->name('changelog');

Route::get('/test/test', function(){
    phpinfo();
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
