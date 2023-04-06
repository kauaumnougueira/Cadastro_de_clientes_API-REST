<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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

Route::post('/cadastrar', [ClienteController::class, 'cadastro']);

Route::get('/clientes', [ClienteController::class, 'getAllClientes']);
Route::get('/pesquisa/{dado}', [ClienteController::class, 'pesquisarPorNomeOrEmail']);
Route::get('/cliente/{id}', [ClienteController::class, 'getClienteId']);

Route::delete('/delete-cliente/{id}', [ClienteController::class, 'deleteCliente']);

Route::post('/update/{id}', [ClienteController::class, 'updateCliente']);
