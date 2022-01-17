<?php

use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function (){
    Route::post('login', [AuthController::class, 'login']);
});


Route::post('user/add/comment', [AchievementsController::class, 'addComment']);

Route::post('user/watch/{lesson}/lesson', [AchievementsController::class, 'watchLesson']);

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
