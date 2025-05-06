<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\TicketController as ClienteTicketController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
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
    Route::get('/dashboard', [ClienteTicketController::class, 'index'])->name('cliente.dashboard');
    Route::get('/ticket/novo', [ClienteTicketController::class, 'create'])->name('cliente.ticket.create');
    Route::post('/ticket', [ClienteTicketController::class, 'store'])
    ->middleware(['throttle:3,1']) // permite 3 tickets por minuto
    ->name('cliente.ticket.store');
    Route::get('/ticket/{id}', [ClienteTicketController::class, 'show'])->name('cliente.ticket.show');
    Route::post('/ticket/{id}/responder', [ClienteTicketController::class, 'responder'])
    ->middleware(['throttle:5,1']) // 5 requisições por minuto
    ->name('cliente.ticket.reply');
    Route::get('/ticket/{id}/respostas', [ClienteTicketController::class, 'respostasJson'])->name('cliente.ticket.respostas');
    Route::post('/ticket/resposta/{id}/mark-read', [ClienteTicketController::class, 'markRespostaAsRead']);
    Route::post('/cliente/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('cliente.notifications.markAsRead');




});


Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/tickets/{id}/responder', [AdminTicketController::class, 'responder'])->name('admin.tickets.reply');
    Route::post('/tickets/{id}/status', [AdminTicketController::class, 'atualizarStatus'])->name('admin.tickets.status');
    Route::get('/ticket/{id}/respostas', [AdminTicketController::class, 'respostasJson'])->name('admin.ticket.respostas');
    Route::post('/tickets/resposta/{id}/mark-read', [AdminTicketController::class, 'markRespostaAsRead']);
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::patch('/users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('admin.users.toggle');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');

});


require __DIR__.'/auth.php';
