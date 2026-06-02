<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::get('/', [CharacterController::class, 'viewer'])->name('home');
Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
Route::post('/characters', [CharacterController::class, 'store'])->name('characters.store');
Route::get('/characters/{character}/edit', [CharacterController::class, 'edit'])->name('characters.edit');
Route::put('/characters/{character}', [CharacterController::class, 'update'])->name('characters.update');
Route::delete('/characters/{character}', [CharacterController::class, 'destroy'])->name('characters.destroy');
Route::get('/api/characters', [CharacterController::class, 'apiIndex']);
