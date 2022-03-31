<?php

use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\TokenJTWController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 1. Endpoint: Create jtw token
Route::post('/create-token', [TokenJTWController::class, 'store']);

// 2. Endpoint: Store short url
Route::post('/shorturl', [ShortUrlController::class, 'store'])->middleware('auth_jtw');

// 3. Endpoint: Open long url from short one
Route::get('/shorturl/{name_url_short}', [ShortUrlController::class, 'openurl'])->middleware('auth_jtw');


