<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TransactionController;

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
ROute::post('/register', [UserController::class, 'register']);
ROute::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [UserController::class, 'logout']);
    //rooms page
    Route::get('/rooms/{id}', [RoomController::class, 'index']);
    Route::get('/room/{id}', [RoomController::class, 'show']);
    //create room
    Route::get('/search/{user}', [UserController::class, 'search']);
    Route::post('/create-room', [RoomController::class, 'store']);
    //update participant
    Route::put('/participant-add', [ParticipantController::class, 'addExpense']);
    Route::put('/participant-remove', [ParticipantController::class, 'removeExpense']);
    //transactions
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
});
