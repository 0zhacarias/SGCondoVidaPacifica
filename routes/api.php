<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('responsaveis', [App\Http\Controllers\ApiTickeckController::class, 'getResponsaveis']);
Route::get('projecto-responsaveis', [App\Http\Controllers\ApiTickeckController::class, 'getProjectoResponsaveis']);
Route::get('responsavel-projectos', [App\Http\Controllers\ApiTickeckController::class, 'responsavelTodosProjectos']);
Route::get('chefe-area', [App\Http\Controllers\ApiTickeckController::class, 'chefeAreaMembros']);
Route::get('projectos', [App\Http\Controllers\ApiTickeckController::class, 'getProjectos']);
Route::post('responsavel', [App\Http\Controllers\ApiTickeckController::class, 'getResponsavel']);
Route::post('students', [App\Http\Controllers\ApiController::class, 'createStudent']);
Route::post('/adicionar-tarefa-tecnico', [App\Http\Controllers\ApiTickeckController::class, 'adicionar_tarefa_tecnico']);

Route::put('students/{id}', [App\Http\Controllers\ApiController::class, 'updateStudent']);
Route::delete('students/{id}', [App\Http\Controllers\ApiController::class, 'deleteStudent']);
