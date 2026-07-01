<?php

use App\Livewire\Admin\Articles;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Reservations;
use App\Livewire\Admin\Desiderata;
use App\Livewire\Admin\Surveys;
use App\Livewire\Admin\Pages;
use App\Livewire\Admin\Clearances;
use App\Livewire\Admin\Memberships;
use App\Livewire\Admin\Podcasts;
use App\Livewire\Admin\Analytics;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Admin Livewire + Shadcn Routes
Route::prefix('admin')->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/articles', Articles::class)->name('admin.articles');
    Route::get('/users', Users::class)->name('admin.users');
    Route::get('/reservations', Reservations::class)->name('admin.reservations');
    Route::get('/desiderata', Desiderata::class)->name('admin.desiderata');
    Route::get('/surveys', Surveys::class)->name('admin.surveys');
    Route::get('/pages', Pages::class)->name('admin.pages');
    Route::get('/clearances', Clearances::class)->name('admin.clearances');
    Route::get('/memberships', Memberships::class)->name('admin.memberships');
    Route::get('/podcasts', Podcasts::class)->name('admin.podcasts');
    Route::get('/analytics', Analytics::class)->name('admin.analytics');
});
