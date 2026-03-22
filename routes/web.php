<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DbConnController;

Route::get('/', [DbConnController::class, 'index'])->name('home');

// Route::get('/add-proponent', [DbConnController::class, 'addProponent']);
// Route::get('/edit-proponent/{id}', [DbConnController::class, 'editProponent']);
// Route::get('/delete-proponent/{id}', [DbConnController::class, 'deleteProponent']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


