<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'is_admin'])->name('home');


// Route::get('/', Index::class);
Route::get('/dashboard', Index::class)->name('dashboard')->middleware(['auth', 'is_admin']);
Route::get('/dashboard/users', \App\Livewire\Dashboard\User\Index::class)->name('dashboard.users')->middleware(['auth', 'is_admin']);
Route::get('/dashboard/roles', \App\Livewire\Dashboard\Role\Index::class)->name('dashboard.roles')->middleware(['auth', 'is_admin']);
Route::get('/dashboard/events', \App\Livewire\Dashboard\Event\Index::class)->name('dashboard.events')->middleware(['auth', 'is_admin']);
Route::get('/dashboard/events/{eventId}', \App\Livewire\Dashboard\Event\Detail::class)
    ->name('dashboard.events.detail')->middleware(['auth', 'is_admin']);
Route::get('/dashboard/events/{eventId}/qr', \App\Livewire\Dashboard\Event\QrGenerate::class)->name('dashboard.qr')->middleware(['auth', 'is_admin']);
// Route::get('/dashboard/events/{eventId}/attendances', \App\Livewire\Dashboard\Event\Attendances::class)
//     ->name('dashboard.events.attendances');
Route::get('/dashboard/event-committees', \App\Livewire\Dashboard\EventCommittee\Index::class)->name('dashboard.eventCommittees')->middleware(['auth', 'is_admin']);

// Route::get('/dashboard/users/{user}', \App\Livewire\Dashboard\User\Show::class)->name('users.show');

Route::get('/user/event', \App\Livewire\User\Event\Index::class)->name('user.event');




// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
