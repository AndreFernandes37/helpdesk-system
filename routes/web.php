<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\TicketController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', [TicketController::class, 'index'])->name('cliente.dashboard');
    Route::get('/ticket/novo', [TicketController::class, 'create'])->name('cliente.ticket.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('cliente.ticket.store');
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('cliente.ticket.show');
    Route::post('/ticket/{id}/responder', [TicketController::class, 'responder'])->name('cliente.ticket.reply');

});


Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/tickets/{id}/responder', [AdminTicketController::class, 'responder'])->name('admin.tickets.reply');
    Route::post('/tickets/{id}/status', [AdminTicketController::class, 'atualizarStatus'])->name('admin.tickets.status');
});


require __DIR__.'/auth.php';
