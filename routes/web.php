<?php

use Illuminate\Support\Facades\Route;
Route::post('/',[App\Http\Controllers\MainController::class,'Main']);
Route::get('/search',[App\Http\Controllers\MainController::class,'searchByRequest']);
Route::get('/293118475064897654323987/makeColleges',[App\Http\Controllers\MainController::class,'Colleges']);
Route::get('/293118475064897654323987/makeProfessors',[App\Http\Controllers\MainController::class,'Proffs']);
Route::get('/vote/{professor}',[App\Http\Controllers\VoteController::class,'New']);
Route::post('/vote/{professor}',[App\Http\Controllers\VoteController::class,'Store']);