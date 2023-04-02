<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, 'index']);
Route::get('/categories', [MainController::class, 'categories']);

Route::get('/{category}', [MainController::class, 'category']);

Route::get('/mobiles/{product?}', [MainController::class, 'product']);
