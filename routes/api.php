<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'v1'], function () {

    Route::post('/register', [UserController::class, 'registerUser']); // Register a user(create).
    Route::post('/login', [UserController::class, 'loginUser']); // Login the user.

    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('/users/{id}', [UserController::class, 'showUser']); // Fetch the user's details.
        Route::post('/users/{id}', [UserController::class, 'updateUser']); // Update the user's details.
        Route::delete('/users/{id}', [UserController::class, 'destroyUser']); // Delete the user.

    });

});

