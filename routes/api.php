<?php

use App\Http\Controllers\CepController;
use App\Http\Controllers\Controller;
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


Route::post('/createuser', [Controller::class, 'createUser']);
Route::get('/getallusers', [Controller::class, 'getAllUser']);
Route::get('/getuser/{id}', [Controller::class, 'getOneUser']);
Route::put('/updateuser/{id}', [Controller::class, 'updateUser']);
Route::delete('/deluser/{id}', [Controller::class, 'deleteUser']);
Route::get('/getAddress/{cep}', [CepController::class, 'getAddress']);

Route::post('/uploadusers', [Controller::class, 'uploadUsers']);
