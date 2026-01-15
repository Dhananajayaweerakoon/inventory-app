<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->group(function () {
    // 1. Dashboard (List items)
    Route::get('/dashboard', [InventoryController::class, 'index'])->name('dashboard');
    
    // 2. Add new items
    Route::post('/items', [InventoryController::class, 'store'])->name('items.store');
    
    // 3. Deduct/Add Stock
    Route::post('/stock/update', [InventoryController::class, 'updateStock'])->name('stock.update');
    
    // 4. View Item History
    Route::get('/items/{item}/history', [InventoryController::class, 'history'])->name('items.history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';