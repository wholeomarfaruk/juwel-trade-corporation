<?php

use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

/*
| Add this line to your project's routes/web.php
*/
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
