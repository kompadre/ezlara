<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TiendaController;
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
Route::resource('tiendas', TiendaController::class)
    ->middleware(\Illuminate\Routing\Middleware\ThrottleRequests::class.':api');

