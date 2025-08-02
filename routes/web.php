<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/dashboard', Index::class)->name('dashboard');
Route::get('/dashboard/users', \App\Livewire\Dashboard\User\Index::class)->name('dashboard.users');
Route::get('/dashboard/roles', \App\Livewire\Dashboard\Role\Index::class)->name('dashboard.roles');
Route::get('/dashboard/events', \App\Livewire\Dashboard\Event\Index::class)->name('dashboard.events');
Route::get('/dashboard/events/{eventId}', \App\Livewire\Dashboard\Event\Detail::class)
    ->name('dashboard.events.detail');
// Route::get('/dashboard/events/{eventId}/attendances', \App\Livewire\Dashboard\Event\Attendances::class)
//     ->name('dashboard.events.attendances');
Route::get('/dashboard/event-committees', \App\Livewire\Dashboard\EventCommittee\Index::class)->name('dashboard.eventCommittees');

// Route::get('/dashboard/users/{user}', \App\Livewire\Dashboard\User\Show::class)->name('users.show');




// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
