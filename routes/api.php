<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\cineController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\FuncionController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\CombosController;
use App\Http\Controllers\GenerosController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PeliculasController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\SSEController;
use App\Http\Controllers\usuarioscontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameLogController;

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
Route::get('/sse', [SSEController::class ,'sendSSE']);

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('mandarcorreo', [AuthController::class, 'mandarcorreo']);
    Route::post('verify-code', 'AuthController@verifyCode')->name('verifyCode');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get ('/activate/{token}', [AuthController::class ,'activate'])->name('activate');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/games', [GameController::class, 'createGame']);
    Route::post('/games/{gameId}/join', [GameController::class, 'joinGame']);
    Route::put('/games/{gameId}/score', [GameController::class, 'updateScore']);
    Route::put('/games/{gameId}/finish', [GameController::class, 'finishGame']);
    Route::post('/game-logs', [GameLogController::class, 'logEvent']);
});