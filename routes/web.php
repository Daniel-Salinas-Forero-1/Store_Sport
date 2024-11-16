<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/p', [ProductController::class, 'index'])->name('products.update');
