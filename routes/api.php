<?php

use App\Http\Controllers\TaskController;
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
 * User password credential grant type authentication check
 */
Route::middleware(['check-api-header', 'auth:api'])->group(function () {
    Route::prefix('v1')->group(static function () {
       Route::resource('task', TaskController::class);
    });
});

