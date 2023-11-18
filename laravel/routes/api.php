<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
});

Route::get('/hello', [\App\Http\Controllers\HelloController::class, '__invoke']);

Route::group(['prefix' => 'users/{user}'], function () {
   Route::get('/profile', [\App\Http\Controllers\Users\User\Profile\GetController::class, '__invoke']);
   Route::post('/profile', [\App\Http\Controllers\Users\User\Profile\PostController::class, '__invoke']);
   Route::patch('/profile', [\App\Http\Controllers\Users\User\Profile\PatchController::class, '__invoke']);
});
