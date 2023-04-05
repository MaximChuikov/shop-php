<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\RolesController;

// Drop unused routes
Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

//Auth routes
Route::group(['middleware' => 'auth'], function () {
    //Customer routes
    Route::group([
        'prefix' => 'person',
        'namespace' => 'Person',
        'as' => 'person.',
    ], function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/received', [OrderController::class, 'received'])->name('orders.setReceived');
    });

    //Admin routes
    Route::group([
        'namespace' => 'Admin',
        'middleware' => 'is_admin'
    ], function () {
        Route::resource('categories', CategoryController::class);

        Route::group([
            'prefix' => 'admin',
        ], function () {
            Route::resource('categories', CategoryController::class);
            Route::get('/roles', [RolesController::class, 'index'])->name('roles');
            Route::post('/roles', [RolesController::class, 'addRole'])->name('roles.add');
        });
    });

    //Seller routes
    Route::group([
        'namespace' => 'Admin',
        'middleware' => 'seller'
    ], function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('home');
        Route::resource('products', ProductController::class);

        Route::group([
            'prefix' => 'admin',
        ], function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('home');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::post('/orders/ahead', [OrderController::class, 'ahead'])->name('orders.ahead');

            Route::resource('products', ProductController::class);
        });
    });
});

//Basket routes
Route::group(['prefix' => 'basket'], function () {
    Route::post('/add/{id}', [BasketController::class, 'basketAdd'])->name('basket-add');

    Route::group([
        'middleware' => 'basket_not_empty',
    ], function () {
        Route::get('/', [BasketController::class, 'basket'])->name('basket');
        Route::get('/place', [BasketController::class, 'basketPlace'])->name('basket-place');
        Route::post('/remove/{id}', [BasketController::class, 'basketRemove'])->name('basket-remove');
        Route::post('/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
    });
});
//Free routes
Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout');
Route::get('/categories', [MainController::class, 'categories'])->name('categories');
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/all-categories', [MainController::class, 'categories'])->name('all-categories');
Route::get('/{category}', [MainController::class, 'category'])->name('category');
Route::get('/{category}/{product}', [MainController::class, 'product'])->name('product');
