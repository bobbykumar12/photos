<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GooglePhotoController;

use App\Http\Controllers\GoogleController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});



Route::get('/', function () {
    // dd('uyguyg');
    return view('landing'); 
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);



Route::get('/fetch-photos', [GooglePhotoController::class, 'fetchAndSavePhotos']);
Route::get('/banner-photos', [GooglePhotoController::class, 'showBannerPhotos'])->name('banners');
Route::get('/photo/{userId}/{photoId}', [GooglePhotoController::class, 'show'])->name('photo.show');
Route::get('/user/{userId}/photos', [GooglePhotoController::class, 'showPhotos'])->name('user.photos');


Route::get('/employee/code', [GoogleController::class, 'showEmployeeCodeForm'])->name('employee.code');
Route::post('/employee/verify', [GoogleController::class, 'verifyEmployeeCode'])->name('employee.verify');

Route::middleware('web')->group(function() {
    Route::get('/employee/welcome', [GoogleController::class, 'welcomePage'])->name('employee.welcome');
});


Route::get('/logout', [GoogleController::class, 'logout']);

