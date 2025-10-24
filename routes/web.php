<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/todo',[TodoController::class,'store'])->name('todo.post');
Route::get('/excel',[TodoController::class,'excel'])->name('todo.excel');
Route::get('/chart',[TodoController::class,'summary'])->name('todo.chart');
