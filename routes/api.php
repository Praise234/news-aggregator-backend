<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TempFetcher;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Auth\ChangePasswordController;


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

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->get('/articles', [ArticleController::class, 'index']);
Route::middleware(['auth:sanctum'])->get('/articles/{id}', [ArticleController::class, 'show']); 


Route::middleware(['auth:sanctum'])->patch('/preferences', [PreferenceController::class, 'store']);

Route::middleware(['auth:sanctum'])->get('/preferences', [PreferenceController::class, 'show']);


Route::middleware(['auth:sanctum'])->post('/change-password', [ChangePasswordController::class, 'changePassword']);




