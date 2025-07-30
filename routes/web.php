<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\ZoneController;
Route::get('/', function () {
    Log::info("Home page: ");
    if (!Auth::check()) {
        return redirect('/login/v2');
    }
    
    Log::info("The user role is: ".Auth::user()->role);
    // Check user role and redirect accordingly
    if (Auth::user()->role === 'Lawyer') {
        return redirect('/dashboard/lawyer');
    }




    return redirect('/dashboard/v2'); // Default for other roles
});

Route::get('/dashboard/v2', [MainController::class, 'dashboardV2'])
    ->name('dashboard-v2')
    ->middleware(['auth']); 


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');




Route::get('/login/v2',  [MainController::class, 'loginV2'])->name('login-v2');

Route::post('/login', [LoginController::class, 'login'])->name('login.post'); 

Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/settings/general', function () {
    return view('settings.general');
})->name('settings.general');

Route::get('/settings/profile', function () {
    return view('settings.profile');
})->name('settings.profile');

Route::get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users');


Route::prefix('occurrence')->name('occurrence.')->middleware('auth')->group(function () {
    Route::get('/', [OccurrenceController::class, 'index'])->name('index');
    Route::get('/create', [OccurrenceController::class, 'create'])->name('create');
    Route::post('/store', [OccurrenceController::class, 'store'])->name('store');

      // Add Your Input
    Route::get('/{occurrence}/input', [OccurrenceController::class, 'input'])->name('input');

    // Edit/Update
    Route::get('/{occurrence}/edit', [OccurrenceController::class, 'edit'])->name('edit');
    Route::put('/{occurrence}', [OccurrenceController::class, 'update'])->name('update');

    // Delete
    Route::delete('/{id}', [OccurrenceController::class, 'destroy'])->name('destroy');

    Route::get('/{id}/edit', [OccurrenceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OccurrenceController::class, 'update'])->name('update');

    Route::post('/{occurrence}/input', [OccurrenceController::class, 'input'])->name('input');


});

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');

});

Route::prefix('other')->middleware(['auth'])->group(function () {
    Route::get('/feedback', [OtherController::class, 'feedbackForm'])->name('other.feedback');
    Route::post('/feedback', [OtherController::class, 'submitFeedback'])->name('other.feedback.submit');
    
    Route::get('/help', [OtherController::class, 'helpPage'])->name('other.help');
    Route::get('/all', [OtherController::class, 'indexFeedback'])->name('other.feedback.index');





});


Route::middleware(['auth'])->prefix('zones')->group(function () {
    Route::get('/', [ZoneController::class, 'index'])->name('zones.index');           // View all zones
    Route::get('/create', [ZoneController::class, 'create'])->name('zones.create');   // Show add form
    Route::post('/store', [ZoneController::class, 'store'])->name('zones.store');     // Store zone
    Route::get('/{id}/edit', [ZoneController::class, 'edit'])->name('zones.edit');    // Edit form
    Route::put('/{id}', [ZoneController::class, 'update'])->name('zones.update');     // Update zone
    Route::delete('/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');// Delete zone


    Route::get('/hostels', [ZoneController::class, 'hostelIndex'])->name('hostels.index');
    Route::get('/hostels/create', [ZoneController::class, 'hostelCreate'])->name('hostels.create');
    Route::post('/hostels/store', [ZoneController::class, 'hostelStore'])->name('hostels.store');
    Route::get('/hostels/{id}/edit', [ZoneController::class, 'hostelEdit'])->name('hostels.edit');
    Route::put('/hostels/{id}', [ZoneController::class, 'hostelUpdate'])->name('hostels.update');
    Route::delete('/hostels/{id}', [ZoneController::class, 'hostelDestroy'])->name('hostels.destroy');
});

