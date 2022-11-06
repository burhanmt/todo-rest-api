<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

/**
 * There is no authentication in these routes. But I use "api" middleware to take advantage of
 * Laravel throttle rate-limiting. Because api middleware has default throttle 60,1 value. It means
 * 60 requests allowed for per minute.
 */
Route::middleware(['check-api-header', 'api'])->group(function () {
    Route::prefix('v1')->group(static function () {
        Route::post('/user/register', [UserController::class, 'register'])->name('user.register');
    });
});

/**
 * User password credential grant type authentication check
 */
Route::middleware(['check-api-header', 'auth:api'])->group(function () {
    Route::prefix('v1')->group(static function () {
       Route::resource('task', TaskController::class)->except(['create', 'edit']);
    });
});

