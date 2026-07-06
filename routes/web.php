<?php

use App\Livewire\Admin\Articles;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Reservations;
use App\Livewire\Admin\Desiderata;
use App\Livewire\Admin\Surveys;
use App\Livewire\Admin\Pages;
use App\Livewire\Admin\Clearances;
use App\Livewire\Admin\Podcasts;
use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', Login::class)->name('login');
});

// Admin Livewire + Shadcn Routes (Protected by auth middleware)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/articles', Articles::class)->name('admin.articles');
    Route::get('/users', Users::class)->name('admin.users');
    Route::get('/reservations', Reservations::class)->name('admin.reservations');
    Route::get('/reservations/{id}', \App\Livewire\Admin\ReservationDetail::class)->name('admin.reservations.detail');
    Route::post('/reservations/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateReservationStatus']);
    Route::get('/desiderata', Desiderata::class)->name('admin.desiderata');
    Route::get('/surveys', Surveys::class)->name('admin.surveys');
    Route::get('/pages', Pages::class)->name('admin.pages');
    Route::get('/clearances', Clearances::class)->name('admin.clearances');
    Route::get('/podcasts', Podcasts::class)->name('admin.podcasts');
    Route::get('/analytics', Analytics::class)->name('admin.analytics');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('admin.logout');
});
