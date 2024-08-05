<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/refresh', [UserController::class, 'refresh'])->name('refresh');
    Route::post('me', [UserController::class, 'me'])->name('me');
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('index');
    Route::get('/criando', [ProdutoController::class, 'create'])->name('criando');
    Route::post('/criado', [ProdutoController::class, 'store'])->name('criado');
    Route::get('/editando/{id}', [ProdutoController::class, 'edit'])->name('editando');
    Route::post('/editado/{id}', [ProdutoController::class, 'update'])->name('editado');
    Route::get('/deletando/{id}', [ProdutoController::class, 'destroy'])->name('deletando');
    Route::post('/deletado/{id}', [ProdutoController::class, 'delete'])->name('deletado');
    Route::get('/buscando/{produto}', [ProdutoController::class, 'show'])->name('buscando');

});