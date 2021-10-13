<?php

use App\Http\Controllers\PekerjaanController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('soal1');
});

Route::get('soal1', [PekerjaanController::class, 'soalno1']);

Route::resource('soal2', PekerjaanController::class);