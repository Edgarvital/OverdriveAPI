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

Route::prefix('pessoa')->name('pessoa.')->group(function (){
    Route::get('/', [\App\Http\Controllers\Api\PessoaController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Api\PessoaController::class, 'store'])->name('store');
    Route::get('/consultarPeloNome', [\App\Http\Controllers\Api\PessoaController::class, 'consultarPeloNome'])->name('buscarPeloNome');
    Route::post('/{id}', [\App\Http\Controllers\Api\PessoaController::class, 'update'])->name('update');
});
